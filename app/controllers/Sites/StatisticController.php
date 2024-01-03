<?php

namespace Sites;

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

    $Site = Controller::model("Site");
    if (isset($Route->params->id)) {
      $Site->select($Route->params->id);
    }

    if (!$Site->isAvailable()) {
      header("Location: " . APPURL . "/sites");
      exit;
    }
    $this->setVariable("Site", $Site);

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

    $this->view("sites/statistic");
  }

  private function totalCharge()
  {
    $this->resp->result = 0;
    $Site = $this->getVariable("Site");

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
        ->where("site_id", "=", $Site->get("id"))
        ->whereBetween(TABLE_PREFIX . TABLE_PAYMENTS . '.date', $start->format('Y-m-d'), $end->format('Y-m-d'))
        ->get();

      $data[] = (int)$res[0]->total;

      $res = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->select(DB::raw("SUM(money) as total"))
        ->where("site_id", "=", $Site->get("id"))
        ->where("type_code", "=", "ORDER")
        ->whereBetween(TABLE_PREFIX . TABLE_FLUCTUATIONS . '.date', $start->format('Y-m-d'), $end->format('Y-m-d'))
        ->get();

      $data[] = (int)$res[0]->total;

      $res = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->select(DB::raw("SUM(money) as total"))
        ->where("site_id", "=", $Site->get("id"))
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
    $Site = $this->getVariable("Site");
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
      $query = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->select(
          DB::raw("SUM(case when type_code = 'ORDER' then 1 else 0 end) as countCompleted"),
          DB::raw("SUM(case when type_code = 'REFUND_ORDER' then 1 else 0 end) as countRefund"),
        )
        ->where("site_id", "=", $Site->get("id"))
        ->whereBetween(TABLE_PREFIX . TABLE_FLUCTUATIONS . '.date', $start->format('Y-m-d'), $end->format('Y-m-d'));

      if ($Service->isAvailable()) {
        $query->where("seeding_type", "=", $Service->get("idname"));
      }

      $res = $query->get();
      $OrderCountCompleted = (int)$res[0]->countCompleted;
      $OrderCountRefund = (int)$res[0]->countRefund;
      $OrderCountAll = $OrderCountCompleted + $OrderCountRefund;

      $data = [$OrderCountAll, $OrderCountCompleted, $OrderCountRefund];
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $data;
    $this->jsonecho();
  }
}
