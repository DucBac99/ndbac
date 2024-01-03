<?php

namespace Dashboard;

use Controller;
use Input;
use DateTimeZone;
use DB;

/**
 * Dashboard Controller
 */
class DashboardController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");
    $Route = $this->getVariable("Route");
    $AuthSite = $this->getVariable("AuthSite");

    // Auth
    if (!$AuthUser) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $Service = Controller::model("Service");
    if (Input::get("order_type")) {
      $Service->select(Input::get("order_type"));
    }

    if (($AuthUser->isViewer() && Input::get("viewer") == 1) || $AuthUser->isAdmin()) {
      $is_show_all = true;
    } else {
      $is_show_all = false;
    }

    $this->setVariable("Service", $Service)
      ->setVariable("is_show_all", $is_show_all);

    if (Input::get("action") == "TotalCharge") {
      $this->totalCharge();
    } else if (Input::get("action") == "TotalSpend") {
      $this->totalSpend();
    } else if (Input::get("action") == "TotalRefund") {
      $this->totalRefund();
    } else if (Input::get("action") == "TotalOrder") {
      $this->totalOrder();
    } else if (Input::get("action") == "TotalOrder2") {
      $this->totalOrder2();
    }


    $fluctuations = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
      ->leftJoin(
        TABLE_PREFIX . TABLE_USERS,
        TABLE_PREFIX . TABLE_FLUCTUATIONS . ".user_id",
        "=",
        TABLE_PREFIX . TABLE_USERS . ".id"
      )
      ->leftJoin(
        TABLE_PREFIX . TABLE_SITES,
        TABLE_PREFIX . TABLE_USERS . ".site_id",
        "=",
        TABLE_PREFIX . TABLE_SITES . ".id"
      )
      ->where(TABLE_PREFIX . TABLE_SITES . ".id", "=", $AuthSite->get("id"))
      ->where(TABLE_PREFIX . TABLE_FLUCTUATIONS . ".type_code", "=", "ORDER")
      ->select([
        TABLE_PREFIX . TABLE_FLUCTUATIONS . ".date",
        TABLE_PREFIX . TABLE_FLUCTUATIONS . ".content",
        TABLE_PREFIX . TABLE_USERS . ".firstname",
        TABLE_PREFIX . TABLE_USERS . ".lastname",
      ])
      ->orderBy(TABLE_PREFIX . TABLE_FLUCTUATIONS . ".id", "DESC")
      ->limit(7)
      ->get();

    $this->setVariable("fluctuations", $fluctuations);

    $Services = DB::table(TABLE_PREFIX . TABLE_SERVICES)
      ->where("is_public", "=", 1)
      ->get();
    $this->setVariable("Services", $Services);

    $this->view("dashboard/dashboard");
  }

  private function totalCharge()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");
    $Service = $this->getVariable("Service");
    $is_show_all = $this->getVariable("is_show_all");

    $date = new \Moment\Moment("now", date_default_timezone_get());
    /** charge money */

    $data = [];
    try {
      $query = DB::table(TABLE_PREFIX . TABLE_PAYMENTS)
        ->select(DB::raw("SUM(total) as total"))
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $res = $query->get();
      $data[] = (int)$res[0]->total;

      $fromdate = $date->startOf("month")->cloning();
      $todate = $date->endOf("month")->cloning();

      $query = DB::table(TABLE_PREFIX . TABLE_PAYMENTS)
        ->whereBetween("date", $fromdate->format("Y-m-d H:i:s"), $todate->format("Y-m-d H:i:s"))
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $query->select(DB::raw("SUM(total) as total"));
      $res = $query->get();
      $data[] = (int)$res[0]->total;


      // $date = new \Moment\Moment("now", date_default_timezone_get());
      // $fromdate = $date->startOf("day")->startOf("week")->addDays(1)->cloning();
      // $fromdate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      // $todate = $date->endOf("day")->endOf("week")->addDays(1)->cloning();
      // $todate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      $query = DB::table(TABLE_PREFIX . TABLE_PAYMENTS)
        ->where(DB::raw("WEEK(date, 1)"), "=", DB::raw("WEEK(CURDATE(), 1)"))
        // ->whereBetween("date", $fromdate->format("Y-m-d H:i:s"), $todate->format("Y-m-d H:i:s"))
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $query->select(DB::raw("SUM(total) as total"));
      // echo $query->getQuery()->getRawSql();
      $res = $query->get();
      $data[] = (int)$res[0]->total;



      // theo ngày
      $date = new \Moment\Moment("now", date_default_timezone_get());
      $fromdate = $date->cloning()->startOf('day');
      $todate = $date->cloning();


      $query = DB::table(TABLE_PREFIX . TABLE_PAYMENTS)
        ->whereBetween("date", $fromdate->format("Y-m-d H:i:s"), $todate->format("Y-m-d H:i:s"))
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $query->select(DB::raw("SUM(total) as total"));
      $res = $query->get();

      $data[] = (int)$res[0]->total;
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $data;
    $this->jsonecho();
  }

  private function totalSpend()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");
    $is_show_all = $this->getVariable("is_show_all");


    /** charge money */

    $data = [];
    try {

      $query = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->select(DB::raw("SUM(money) as total"))
        ->where("type_code", "=", "ORDER")
        ->where("site_id", "=", $AuthSite->get("id"));

      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $res = $query->get();
      $data[] = (int)$res[0]->total;

      $date = new \Moment\Moment("now", date_default_timezone_get());
      $fromdate = $date->startOf("month")->cloning();
      // $fromdate->setTimezone(new DateTimeZone(date_default_timezone_get()));
      $todate = $date->endOf("month")->cloning();
      // $todate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      $query = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->whereBetween("date", $fromdate->format("Y-m-d H:i:s"), $todate->format("Y-m-d H:i:s"))
        ->where("type_code", "=", "ORDER")
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $query->select(DB::raw("SUM(money) as total"));
      $res = $query->get();
      $data[] = (int)$res[0]->total;


      // $date = new \Moment\Moment("now", date_default_timezone_get());
      // $fromdate = $date->cloning()->startOf("week")->addDays(1);
      // // $fromdate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      // $todate = $date->cloning();
      // $todate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      $query = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->where(DB::raw("WEEK(date, 1)"), "=", DB::raw("WEEK(CURDATE(), 1)"))
        ->where("type_code", "=", "ORDER")
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $query->select(DB::raw("SUM(money) as total"));
      // echo $query->getQuery()->getRawSql();
      $res = $query->get();
      $data[] = (int)$res[0]->total;

      // theo ngày
      $date = new \Moment\Moment("now", date_default_timezone_get());
      $fromdate = $date->cloning()->startOf('day');
      // $fromdate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      $todate = $date->cloning();
      // $todate->setTimezone(new DateTimeZone(date_default_timezone_get()));


      $query = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->whereBetween("date", $fromdate->format("Y-m-d H:i:s"), $todate->format("Y-m-d H:i:s"))
        ->where("type_code", "=", "ORDER")
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $query->select(DB::raw("SUM(money) as total"));
      $res = $query->get();

      $data[] = (int)$res[0]->total;
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $data;
    $this->jsonecho();
  }

  private function totalRefund()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");
    $is_show_all = $this->getVariable("is_show_all");
    /** charge money */
    $data = [];
    try {

      $query = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->select(DB::raw("SUM(money) as total"))
        ->where("type_code", "=", "REFUND_ORDER")
        ->where("site_id", "=", $AuthSite->get("id"));

      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $res = $query->get();
      $data[] = (int)$res[0]->total;

      $date = new \Moment\Moment("now", date_default_timezone_get());
      $fromdate = $date->startOf("month")->cloning();
      // $fromdate->setTimezone(new DateTimeZone(date_default_timezone_get()));
      $todate = $date->endOf("month")->cloning();
      // $todate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      $query = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->whereBetween("date", $fromdate->format("Y-m-d H:i:s"), $todate->format("Y-m-d H:i:s"))
        ->where("type_code", "=", "REFUND_ORDER")
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $query->select(DB::raw("SUM(money) as total"));
      $res = $query->get();
      $data[] = (int)$res[0]->total;


      // $date = new \Moment\Moment("now", date_default_timezone_get());
      // $fromdate = $date->cloning()->startOf("week")->addDays(1);
      // // $fromdate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      // $todate = $date->cloning();
      // $todate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      $query = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->where(DB::raw("WEEK(date, 1)"), "=", DB::raw("WEEK(CURDATE(), 1)"))
        ->where("type_code", "=", "REFUND_ORDER")
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $query->select(DB::raw("SUM(money) as total"));
      // echo $query->getQuery()->getRawSql();
      $res = $query->get();
      $data[] = (int)$res[0]->total;

      // theo ngày
      $date = new \Moment\Moment("now", date_default_timezone_get());
      $fromdate = $date->cloning()->startOf('day');
      // $fromdate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      $todate = $date->cloning();
      // $todate->setTimezone(new DateTimeZone(date_default_timezone_get()));


      $query = DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->whereBetween("date", $fromdate->format("Y-m-d H:i:s"), $todate->format("Y-m-d H:i:s"))
        ->where("type_code", "=", "REFUND_ORDER")
        ->where("site_id", "=", $AuthSite->get("id"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      $query->select(DB::raw("SUM(money) as total"));
      $res = $query->get();

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
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");
    $Service = $this->getVariable("Service");
    $is_show_all = $this->getVariable("is_show_all");


    $data = [];
    try {
      // Lấy số lượng đơn hàng đã bán
      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->select(
          DB::raw("COUNT(id) as countAll"),
          DB::raw("SUM(case when status = 'COMPLETED' then 1 else 0 end) as countCompleted"),
          DB::raw("SUM(case when status = 'REFUND' then 1 else 0 end) as countRefund"),
          DB::raw("SUM(case when status = 'RUNNING' then 1 else 0 end) as countProcess"),
        );
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
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

  private function totalOrder2()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");
    $Service = $this->getVariable("Service");
    $is_show_all = $this->getVariable("is_show_all");

    $data = [];
    try {

      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where(DB::raw("YEAR(created_at)"), "=", date('Y'))
        ->where(DB::raw("MONTH(created_at)"), "=", date('m'))
        ->select(DB::raw("COUNT(id) as total"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      if ($Service->isAvailable()) {
        $query->where("seeding_type", "=", $Service->get("idname"));
      }
      $res = $query->get();
      $data[0][] = (int)$res[0]->total;

      // theo tuần
      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where(DB::raw("WEEK(created_at, 1)"), "=", DB::raw("WEEK(CURDATE(), 1)"))
        ->select(DB::raw("COUNT(id) as total"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      if ($Service->isAvailable()) {
        $query->where("seeding_type", "=", $Service->get("idname"));
      }
      $res = $query->get();
      $data[0][] = (int)$res[0]->total;

      // theo ngày
      $date = new \Moment\Moment("now", date_default_timezone_get());
      $fromdate = $date->cloning()->startOf('day');
      $fromdate->setTimezone(new DateTimeZone(date_default_timezone_get()));

      $todate = $date->cloning();
      $todate->setTimezone(new DateTimeZone(date_default_timezone_get()));


      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->whereBetween("created_at", $fromdate->format("Y-m-d H:i:s"), $todate->format("Y-m-d H:i:s"))
        ->select(DB::raw("COUNT(id) as total"));
      if (!$is_show_all) {
        $query->where("user_id", "=", $AuthUser->get("id"));
      }
      if ($Service->isAvailable()) {
        $query->where("seeding_type", "=", $Service->get("idname"));
      }
      $res = $query->get();
      $data[0][] = (int)$res[0]->total;


      // Lấy theo status
      $status = ["COMPLETED", "REFUND", "RUNNING"];
      for ($i = 0; $i < count($status); $i++) {
        $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
          ->where(DB::raw("YEAR(created_at)"), "=", date('Y'))
          ->where(DB::raw("MONTH(created_at)"), "=", date('m'))
          ->where("status", "=", $status[$i])
          ->select(DB::raw("COUNT(id) as total"));

        if (!$is_show_all) {
          $query->where("user_id", "=", $AuthUser->get("id"));
        }
        $res = $query->get();
        $data[$i + 1][] = (int)$res[0]->total;


        $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
          ->where(DB::raw("WEEK(created_at, 1)"), "=", DB::raw("WEEK(CURDATE(), 1)"))
          ->where("status", "=", $status[$i])
          ->select(DB::raw("COUNT(id) as total"));
        if (!$is_show_all) {
          $query->where("user_id", "=", $AuthUser->get("id"));
        }
        if ($Service->isAvailable()) {
          $query->where("seeding_type", "=", $Service->get("idname"));
        }
        $res = $query->get();
        $data[$i + 1][] = (int)$res[0]->total;

        // theo ngày
        $date = new \Moment\Moment("now", date_default_timezone_get());
        $fromdate = $date->cloning()->startOf('day');
        $fromdate->setTimezone(new DateTimeZone(date_default_timezone_get()));

        $todate = $date->cloning();
        $todate->setTimezone(new DateTimeZone(date_default_timezone_get()));


        $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
          ->whereBetween("created_at", $fromdate->format("Y-m-d H:i:s"), $todate->format("Y-m-d H:i:s"))
          ->where("status", "=", $status[$i])
          ->select(DB::raw("COUNT(id) as total"));
        if (!$is_show_all) {
          $query->where("user_id", "=", $AuthUser->get("id"));
        }
        $res = $query->get();
        $data[$i + 1][] = (int)$res[0]->total;
      }
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }


    $this->resp->result = 1;
    $this->resp->data = $data;
    $this->jsonecho();
  }
}
