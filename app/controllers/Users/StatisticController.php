<?php

namespace Users;

use Controller;
use DB;
use Input;
use Exception;

/**
 * Statistic Controller
 */
class StatisticController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $Route = $this->getVariable("Route");
    $AuthUser = $this->getVariable("AuthUser");

    // Auth
    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $User = Controller::model("User");
    if (isset($Route->params->id)) {
      $User->select($Route->params->id);
    }

    if (!$User->isAvailable()) {
      header("Location: " . APPURL . "/users");
      exit;
    }
    $this->setVariable("User", $User);

    $Service = Controller::model("Service");
    if (Input::get("order_type")) {
      $Service->select(Input::get("order_type"));
    }

    $this->setVariable("Service", $Service);

    $Services = DB::table(TABLE_PREFIX . TABLE_SERVICES)
      ->where("is_public", "=", 1)
      ->get();
    $this->setVariable("Services", $Services);


    if (Input::get("action") == "TotalCharge") {
      $this->totalCharge();
    } else if (Input::get("action") == "TotalOrder") {
      $this->totalOrder();
    }

    $this->view("users/statistic");
  }

  private function totalCharge()
  {
    $this->resp->result = 0;
    $User = $this->getVariable("User");

    $from = Input::get("from");
    $to = Input::get("to");

    if (!isValidDate($from, 'd/m/Y') || !isValidDate($to, 'd/m/Y')) {
      $this->resp->msg = "Sai định dạng ngày chọn";
      $this->jsonecho();
    }

    $startdate = strtotime(str_replace('/', '-', $from . date(' H:i:s')));
    $start = new \Moment\Moment(date('Y-m-d H:i:s', $startdate), date_default_timezone_get());

    $newdate = strtotime(str_replace('/', '-', $to . date(' H:i:s')));
    $end = new \Moment\Moment(date('Y-m-d H:i:s', $newdate), date_default_timezone_get());

    $data = [];
    try {
      $res = DB::table(TABLE_PREFIX . TABLE_PAYMENTS)
        ->select(DB::raw("SUM(total) as total"))
        ->where("user_id", "=", $User->get("id"))
        ->whereBetween(TABLE_PREFIX . TABLE_PAYMENTS . '.date', $start->format('Y-m-d'), $end->format('Y-m-d'))
        ->get();

      $data[] = (int)$res[0]->total;


      $res = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->select(DB::raw("SUM(money) as total"))
        ->where("user_id", "=", $User->get("id"))
        ->where("type_code", "=", "ADD_BALANCE_ADMIN")
        ->whereBetween(TABLE_PREFIX . TABLE_FLUCTUATIONS . '.date', $start->format('Y-m-d'), $end->format('Y-m-d'))
        ->get();

      $data[] = (int)$res[0]->total;

      $res = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->select(DB::raw("SUM(money) as total"))
        ->where("user_id", "=", $User->get("id"))
        ->where("type_code", "=", "ORDER")
        ->whereBetween(TABLE_PREFIX . TABLE_FLUCTUATIONS . '.date', $start->format('Y-m-d'), $end->format('Y-m-d'))
        ->get();

      $data[] = (int)$res[0]->total;

      $res = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->select(DB::raw("SUM(money) as total"))
        ->where("user_id", "=", $User->get("id"))
        ->where("type_code", "=", "REFUND_ORDER")
        ->whereBetween(TABLE_PREFIX . TABLE_FLUCTUATIONS . '.date', $start->format('Y-m-d'), $end->format('Y-m-d'))
        ->get();

      $data[] = (int)$res[0]->total;
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $data;
    $this->jsonecho();
  }

  private function totalOrder()
  {
    $this->resp->result = 0;
    $User = $this->getVariable("User");
    $Service = $this->getVariable("Service");

    $from = Input::get("from");
    $to = Input::get("to");

    if (!isValidDate($from, 'd/m/Y') || !isValidDate($to, 'd/m/Y')) {
      $this->resp->msg = "Sai định dạng ngày chọn";
      $this->jsonecho();
    }

    $startdate = strtotime(str_replace('/', '-', $from . date(' H:i:s')));
    $start = new \Moment\Moment(date('Y-m-d H:i:s', $startdate), date_default_timezone_get());

    $newdate = strtotime(str_replace('/', '-', $to . date(' H:i:s')));
    $end = new \Moment\Moment(date('Y-m-d H:i:s', $newdate), date_default_timezone_get());

    $data = [];
    try {
      // Lấy số lượng đơn hàng đã bán
      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->select(
          DB::raw("COUNT(id) as countAll"),
          DB::raw("SUM(case when status = 'COMPLETED' then 1 else 0 end) as countCompleted"),
          DB::raw("SUM(case when status = 'REFUND' then 1 else 0 end) as countRefund"),
          DB::raw("SUM(case when status = 'RUNNING' then 1 else 0 end) as countProcess"),
        )
        ->where("user_id", "=", $User->get("id"))
        ->whereBetween(TABLE_PREFIX . TABLE_ORDERS . '.created_at', $start->format('Y-m-d'), $end->format('Y-m-d'));

      if ($Service->isAvailable()) {
        $query->where("seeding_type", "=", $Service->get("idname"));
      }

      $res = $query->get();
      $OrderCountAll = (int)$res[0]->countAll;
      $OrderCountCompleted = (int)$res[0]->countCompleted;
      $OrderCountRefund = (int)$res[0]->countRefund;
      $OrderCountProcess = (int)$res[0]->countProcess;

      $data = [$OrderCountAll, $OrderCountCompleted, $OrderCountRefund, $OrderCountProcess];
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $data;
    $this->jsonecho();
  }
}
