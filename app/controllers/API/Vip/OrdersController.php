<?php

namespace API\Vip;

use Controller;
use Input;
use DB;

/**
 * Orders Controller
 */
class OrdersController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $this->orders();
  }

  public function orders()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");

    $start = (int)Input::get("start");
    $length = (int)Input::get("length");
    $status = Input::get("status");

    $service_name = Input::get("service_name");
    if (!$service_name) {
      $this->resp->error = 'SERVICE_NOT_FOUND';
      $this->resp->msg = 'Service không hợp lệ';
      $this->jsonecho();
    }

    if ($length > 100) {
      $this->resp->error = 'LIMIT_INVALID';
      $this->resp->msg = 'Độ dài không đươc hơn 100';
      $this->jsonecho();
    }

    require_once(APPPATH . '/inc/order-status.inc.php');

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->leftJoin(
          TABLE_PREFIX . TABLE_USERS,
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id",
          "=",
          TABLE_PREFIX . TABLE_USERS . ".id"
        )
        ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".seeding_type", "=", $service_name);

      if (in_array($status, $order_status)) {
        $query->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".status", "=", $status);
      }

      if (!$AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }

      $query->select([
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

      $res = $query
        ->limit($length)
        ->offset($start)
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $res;
    $this->jsonecho();
  }
}
