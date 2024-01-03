<?php

namespace API\Buff;

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
      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->leftJoin(
          TABLE_PREFIX . TABLE_USERS,
          TABLE_PREFIX . TABLE_ORDERS . ".user_id",
          "=",
          TABLE_PREFIX . TABLE_USERS . ".id"
        )
        ->where(TABLE_PREFIX . TABLE_ORDERS . ".seeding_type", "=", $service_name);

      if (in_array($status, $order_status)) {
        $query->where(TABLE_PREFIX . TABLE_ORDERS . ".status", "=", $status);
      }

      if (!$AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }

      $query->select([
        TABLE_PREFIX . TABLE_ORDERS . ".id",
        TABLE_PREFIX . TABLE_ORDERS . ".seeding_uid",
        TABLE_PREFIX . TABLE_ORDERS . ".order_amount",
        TABLE_PREFIX . TABLE_ORDERS . ".start_num",
        TABLE_PREFIX . TABLE_ORDERS . ".reaction_type",
        // TABLE_PREFIX . TABLE_ORDERS . ".seeding_num",
        TABLE_PREFIX . TABLE_ORDERS . ".status",
        TABLE_PREFIX . TABLE_ORDERS . ".note",
        TABLE_PREFIX . TABLE_ORDERS . ".note_extra",
        TABLE_PREFIX . TABLE_ORDERS . ".updated_at",
      ]);

      $query->select([
        DB::raw("COUNT(" . TABLE_PREFIX . TABLE_ORDER_LOGS . ".id) as seeding_num")
      ]);
      $query->leftJoin(TABLE_PREFIX . TABLE_ORDER_LOGS, function ($table) {
        $table->on(
          TABLE_PREFIX . TABLE_ORDER_LOGS . ".order_id",
          "=",
          TABLE_PREFIX . TABLE_ORDERS . ".id"
        );
        $table->on(TABLE_PREFIX . TABLE_ORDER_LOGS . ".status", '=', DB::raw(1));
      })
        ->groupBy(TABLE_PREFIX . TABLE_ORDERS . ".id");

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
