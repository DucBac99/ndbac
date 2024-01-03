<?php

namespace API\Vip;

use Controller;
use Input;
use DB;

/**
 * Order Controller
 */
class OrderController extends \Orders\Vip\ItemController
{
  /**
   * Process
   */
  public function process()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->checkService();
      $this->save();
    } else if (Input::get("action") == "check") {
      $this->order();
    } else if (Input::get("action") == "refund") {
      $this->refund();
    } else if (Input::get("action") == "pause") {
      $this->pause();
    } else if (Input::get("action") == "start") {
      $this->start();
    }
  }

  private function checkService()
  {
    if (!Input::post("service_id")) {
      $this->resp->msg = "Thiếu id dịch vụ";
      $this->jsonecho();
    }

    $Service = Controller::model("Service", Input::post("service_id"));
    if (!$Service->isAvailable() || !$Service->get("is_public")) {
      $this->resp->msg = "Dịch vụ không tồn tại";
      $this->jsonecho();
    }

    $this->setVariable("Service", $Service);
  }

  public function order()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $order_ids = Input::get("order_ids");

    if (!$order_ids) {
      $this->resp->msg = "Thiếu order_ids";
      $this->jsonecho();
    }

    print_r($order_ids);
    $ids = filterListID(explode(',', $order_ids));

    if (count($ids) == 0) {
      $this->resp->msg = "Thiếu order_ids";
      $this->jsonecho();
    }

    if (count($ids) > 200) {
      $this->resp->msg = "Hãy giảm số lượng, tối đa chỉ là 200";
      $this->jsonecho();
    }

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->whereIn(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", $ids)
        ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id", "=", $AuthUser->get("id"))
        ->select([
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".id",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".seeding_uid",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".month",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".reaction_type",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".status",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".note",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".note_extra",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".created_at",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".expired_at",
        ]);
      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    if (count($res) == 0) {
      $this->resp->msg = "Order không tồn tại";
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $res;
    $this->jsonecho();
  }

  public function pause()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    $order_id = Input::get("order_id");

    if (!$order_id) {
      $this->resp->msg = "Thiếu order_id";
      $this->jsonecho();
    }

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", "=", $order_id)
        ->select([
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".id",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".status",
        ]);
      if ($AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }
      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    if (count($res) == 0) {
      $this->resp->msg = "Order không tồn tại";
      $this->jsonecho();
    }

    if ($res[0]->status == 'PAUSED') {
      $this->resp->msg = "Đơn đã dừng";
      $this->jsonecho();
    }

    if ($res[0]->status != 'RUNNING') {
      $this->resp->msg = "Không thể pause với đơn chưa run";
      $this->jsonecho();
    }

    DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
      ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", "=", $order_id)
      ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id", "=", $AuthUser->get("id"))
      ->update([
        'status' => 'PAUSED'
      ]);

    $this->resp->result = 1;
    $this->resp->msg = "Dừng đơn thành công";
    $this->jsonecho();
  }

  public function start()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    $order_id = Input::get("order_id");

    if (!$order_id) {
      $this->resp->msg = "Thiếu order_id";
      $this->jsonecho();
    }

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", "=", $order_id)
        ->select([
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".id",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".status",
        ]);

      if ($AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }
      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    if (count($res) == 0) {
      $this->resp->msg = "Order không tồn tại";
      $this->jsonecho();
    }

    if ($res[0]->status == 'RUNNING') {
      $this->resp->msg = "Đơn đã chạy";
      $this->jsonecho();
    }

    if ($res[0]->status != 'PAUSED') {
      $this->resp->error = "ORDER_NOT_PAUSED";
      $this->resp->msg = "Không thể chạy với đơn chưa pause";
      $this->jsonecho();
    }

    DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
      ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", "=", $order_id)
      ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id", "=", $AuthUser->get("id"))
      ->update([
        'status' => 'RUNNING'
      ]);

    $this->resp->result = 1;
    $this->resp->msg = "Chạy đơn thành công";
    $this->jsonecho();
  }
}
