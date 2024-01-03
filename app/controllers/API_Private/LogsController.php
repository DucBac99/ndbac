<?php

namespace API_Private;

use Controller;
use Input;
use DB;
use Redis;
use stdClass;

/**
 * Logs Controller
 */
class LogsController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $token = Input::get("access_token");
    if (!$token || $token != TOKEN_LOGS) {
      $this->resp->result = false;
      $this->resp->msg = "Thiếu token xác thực";
      $this->jsonecho();
    }

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
      $this->getApi();
    } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    }
  }

  private function getApi()
  {
    $action = Input::get("action");
    switch ($action) {
      case 'check':
        $this->check();
        break;
      case 'check-exist':
        $this->checkExist();
        break;
      case 'get-list-uid':
        $this->getListUid();
        break;
      default:
        # code...
        break;
    }
  }

  private function check()
  {
    $this->resp->result = false;
    $required_fields = ["order_id", "account_uid", "seeding_uid", "seeding_type"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    try {
      $result = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
        ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".order_id", "=", Input::get("order_id"))
        ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".account_uid", "=", Input::get("account_uid"))
        ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".seeding_uid", "=", Input::get("seeding_uid"))
        ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".seeding_type", "=", Input::get("seeding_type"))
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = "Check thất bại";
      $this->jsonecho();
    }

    if (count($result) == 0) {
      $this->resp->result = true;
      $this->resp->check = false;
      $this->resp->msg = "Check thành công";
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->check = true;
    $this->resp->msg = "Check thành công";
    $this->jsonecho();
  }

  private function checkExist()
  {
    $this->resp->result = false;
    $required_fields = ["account_uid", "seeding_type"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    $seeding_types = explode("|", Input::get("seeding_type"));
    try {
      $result = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
        ->select([
          DB::raw("COUNT(*) as total")
        ])
        ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".account_uid", "=", Input::get("account_uid"))
        ->whereIn(TABLE_PREFIX . TABLE_ORDER_LOGS . ".seeding_type", $seeding_types)
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = "Check thất bại";
      $this->jsonecho();
    }

    if ($result[0]->total > 0) {
      $this->resp->check = true;
    } else {
      $this->resp->check = false;
    }

    $this->resp->result = true;
    $this->resp->msg = "Check thành công";
    $this->jsonecho();
  }

  private function getListUid()
  {
    $this->resp->result = false;
    $required_fields = ["seeding_uid", "seeding_type", "limit"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    $limit = intval(Input::get("limit"));
    if (!$limit || $limit < 200) $limit = 50;

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
        ->select([
          TABLE_PREFIX . TABLE_ORDER_LOGS . ".account_uid"
        ])
        ->limit($limit)
        ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".seeding_uid", "=", Input::get("seeding_uid"))
        ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".seeding_type", "=", Input::get("seeding_type"));
      // echo $query->getQuery()->getRawSql();
      $result = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = "Check thất bại";
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->data = array_map(function ($item) {
      return $item->account_uid;
    }, $result);
    $this->resp->msg = "success";
    $this->jsonecho();
  }
}
