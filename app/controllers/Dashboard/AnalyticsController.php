<?php

namespace Dashboard;

use Controller;
use Input;
use stdClass;
use DB;
use Redis;

/**
 * Analytics Controller
 */
class AnalyticsController extends Controller
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

    if (!$AuthUser->get("has_analytics")) {
      header("Location: " . APPURL . "/dashboard");
      exit;
    }

    if ($AuthUser->isAdmin()) {
      if (Input::get("_type") == "query") {
        $this->searchUsers();
      }
    }


    $Roles = Controller::model("Roles");
    $Roles->where("site_id", "=", $AuthSite->get("id"))
      ->where("id", ">", 4)
      ->fetchData();

    $this->setVariable("Roles", $Roles);

    if (Input::post("action") == "get") {
      $this->get();
    } else if (Input::post("action") == "price_calculate") {
      $this->price_calculate();
    } else if (Input::post("action") == "get_data_realtime") {
      $this->get_data_realtime();
    }

    $this->view("dashboard/analytics");
  }

  private function get()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");
    $date_input = explode(" - ", Input::post("date"));

    if (count($date_input) != 2) {
      $this->resp->msg = 'Ngày không hợp lệ';
      $this->jsonecho();
    }

    if (!isValidDate($date_input[0], 'd/m/Y') || !isValidDate($date_input[1], 'd/m/Y')) {
      $this->resp->msg = 'Ngày không hợp lệ';
      $this->jsonecho();
    }

    $startdate = strtotime(str_replace('/', '-', $date_input[0] . date(' H:i:s')));
    $start = new \Moment\Moment(date('Y-m-d H:i:s', $startdate), date_default_timezone_get());

    $newdate = strtotime(str_replace('/', '-', $date_input[1] . date(' H:i:s')));
    $end = new \Moment\Moment(date('Y-m-d H:i:s', $newdate), date_default_timezone_get());


    $User = Controller::model("User");
    if (Input::post("user_id")) {
      $User->select(Input::post("user_id"));
    }

    $data = [];
    try {
      $query = DB::table(TABLE_PREFIX . TABLE_INTERACT_LOGS)
        ->select([
          DB::raw("SUM(" . TABLE_PREFIX . TABLE_INTERACT_LOGS . ".total) as total"),
          TABLE_PREFIX . TABLE_INTERACT_LOGS . '.seeding_type'
        ])
        ->whereBetween(TABLE_PREFIX . TABLE_INTERACT_LOGS . '.date_at', $start->format('Y-m-d'), $end->format('Y-m-d'))
        ->groupBy(TABLE_PREFIX . TABLE_INTERACT_LOGS . '.seeding_type');

      if ($User->isAvailable()) {
        $query->where(TABLE_PREFIX . TABLE_INTERACT_LOGS . '.user_id', '=', $User->get("id"));
      } else {
        $query->where(TABLE_PREFIX . TABLE_INTERACT_LOGS . '.user_id', '=', $AuthUser->get("id"));
      }
      $data = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $data;
    $this->jsonecho();
  }

  private function get_data_realtime()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");

    $User = Controller::model("User");
    if (Input::post("user_id")) {
      $User->select(Input::post("user_id"));
      $user_id = $User->get("id");
    } else {
      $user_id = $AuthUser->get("id");
    }

    $redis = new Redis();
    $redis->connect(REDIS_HOST, REDIS_PORT);
    if (REDIS_PASS != '') {
      $redis->auth(REDIS_PASS);
    }

    $date = new \Moment\Moment('now', 'Asia/Ho_Chi_Minh');

    $key_user_day = REDIS_USER_ID . "_" . $user_id . "_" . $date->format('Y-m-d');
    if ($redis->exists($key_user_day)) {
      $data_day = unserialize($redis->get($key_user_day));
    } else {
      $data_day = new stdClass;
    }

    // Lấy thông tin data theo phút và user_id
    $key_user_minute = REDIS_USER_ID . "_" . $user_id . "_minute";
    if ($redis->exists($key_user_minute)) {
      $data_minute = unserialize($redis->get($key_user_minute));
    } else {
      $data_minute = new stdClass;
    }

    $this->resp->result = 1;
    $this->resp->data = array($data_day, $data_minute);
    $this->jsonecho();
  }

  private function searchUsers()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");

    $term = Input::get("term");
    if (!$term) {
      $this->resp->result = 1;
      $this->resp->data = [];
      $this->jsonecho();
    }

    try {
      $res = DB::table(TABLE_PREFIX . TABLE_USERS)
        ->where("email", "LIKE", $term . '%')
        ->where("has_analytics", "=", 1)
        ->select([
          "id", DB::raw("email as text")
        ])
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }
    $this->resp->result = 1;
    $this->resp->data = $res;
    $this->jsonecho();
  }

  private function price_calculate()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");
    $date_input = explode(" - ", Input::post("date_range"));

    $draw = (int)Input::get("draw");
    if ($draw) {
      $this->resp->draw = $draw;
    }

    if (count($date_input) != 2) {
      $this->resp->msg = 'Ngày không hợp lệ';
      $this->jsonecho();
    }

    if (!isValidDate($date_input[0], 'd/m/Y') || !isValidDate($date_input[1], 'd/m/Y')) {
      $this->resp->msg = 'Ngày không hợp lệ';
      $this->jsonecho();
    }

    $startdate = strtotime(str_replace('/', '-', $date_input[0] . date(' H:i:s')));
    $start = new \Moment\Moment(date('Y-m-d H:i:s', $startdate), date_default_timezone_get());
    $start = $start->startOf("day");

    $newdate = strtotime(str_replace('/', '-', $date_input[1] . date(' H:i:s')));
    $end = new \Moment\Moment(date('Y-m-d H:i:s', $newdate), date_default_timezone_get());
    $end = $end->startOf("day");

    try {
      $subQuery = DB::table(TABLE_PREFIX . TABLE_SERVICE_TITLES)
        ->select(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.title')
        ->where(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.service_id', '=', DB::raw("`" . TABLE_PREFIX . TABLE_SERVICES . "`.`id`"))
        ->where(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.site_id', '=', $AuthSite->get("id"));

      $services = DB::table(TABLE_PREFIX . TABLE_SERVICES)
        ->leftJoin(
          TABLE_PREFIX . TABLE_SERVICE_SETTINGS,
          function ($table) use ($AuthSite) {
            $table->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".service_id", "=", TABLE_PREFIX . TABLE_SERVICES . ".id");
            $table->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".site_id", "=", DB::raw($AuthSite->get("id")));
            $table->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".server_id", "=", DB::raw(1));
          }
        )
        ->where(TABLE_PREFIX . TABLE_SERVICES . ".is_public", "=", 1)
        ->where(TABLE_PREFIX . TABLE_SERVICES . ".group", "=", "facebook")
        ->where(TABLE_PREFIX . TABLE_SERVICES . ".idname", "LIKE", "buff-%")
        ->select([
          TABLE_PREFIX . TABLE_SERVICES . ".title",
          TABLE_PREFIX . TABLE_SERVICES . ".id",
          TABLE_PREFIX . TABLE_SERVICES . ".idname",
          TABLE_PREFIX . TABLE_SERVICES . ".icon",
        ])
        ->select([
          TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".price",
        ])
        ->select(DB::subQuery($subQuery, 'title_extra'))
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    try {
      $res_total_order = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->select([
          DB::raw("count(id) as total_order"),
          "seeding_type"
        ])
        ->where("user_id", "=", $AuthUser->get("id"))
        // ->whereBetween("created_at", $start->format('Y-m-d'), $end->format('Y-m-d'))
        ->groupBy("seeding_type")
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    foreach ($res_total_order as $item) {
      foreach ($services as $service) {
        if ($service->idname == $item->seeding_type) {
          $service->total_order = intval($item->total_order);
        } else {
          $service->total_order = 0;
        }
      }
    }

    $redis = new Redis();
    $redis->connect(REDIS_HOST, REDIS_PORT);
    if (REDIS_PASS != '') {
      $redis->auth(REDIS_PASS);
    }


    $date = new \Moment\Moment('now', 'Asia/Ho_Chi_Minh');
    if ($date->format('Y-m-d') == $end->format('Y-m-d')) {
      $key_user_day = REDIS_USER_ID . "_" . $AuthUser->get("id") . "_" . $date->format('Y-m-d');
      if ($redis->exists($key_user_day)) {
        $data_day = unserialize($redis->get($key_user_day));
      } else {
        $data_day = new stdClass;
      }
      foreach ($services as $service) {
        if (!empty($data_day->{$service->idname})) {
          $service->total_interact = intval($data_day->{$service->idname});
        } else {
          $service->total_interact = 0;
        }
      }
    }

    try {
      $res_total_interact_warranty = DB::table(TABLE_PREFIX . TABLE_WARRANTY_LOGS)
        ->select([
          DB::raw("SUM(total) as total"),
          "seeding_type"
        ])
        ->where("user_id", "=", $AuthUser->get("id"))
        ->whereBetween("date_at", $start->cloning()->startOf("day")->format('Y-m-d H:i:s'), $end->cloning()->endOf("day")->format('Y-m-d H:i:s'))
        ->groupBy("seeding_type")
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    foreach ($res_total_interact_warranty as $item) {
      foreach ($services as $service) {
        if ($service->idname == $item->seeding_type) {
          $service->total_interact_warranty = intval($item->total);
          break;
        }
      }
    }

    if ($date->format('Y-m-d') != $start->format('Y-m-d')) {
      try {
        $res_total_interact = DB::table(TABLE_PREFIX . TABLE_INTERACT_LOGS)
          ->select([
            "total",
            "seeding_type"
          ])
          ->where("user_id", "=", $AuthUser->get("id"))
          ->whereBetween("date_at", $start->format('Y-m-d'), $end->format('Y-m-d'))
          ->get();
      } catch (\Exception $ex) {
        $this->resp->msg = $ex->getMessage();
        $this->jsonecho();
      }

      foreach ($res_total_interact as $item) {
        foreach ($services as $service) {
          if ($service->idname == $item->seeding_type) {
            if (!empty($service->total_interact)) {
              $service->total_interact = intval($item->total) + $service->total_interact;
            } else {
              $service->total_interact = intval($item->total);
            }
            break;
          }
        }
      }
    }

    $this->resp->result = 1;
    $this->resp->data = $services;
    $this->resp->role = $AuthUser->get("account_type");
    $this->jsonecho();
  }
}
