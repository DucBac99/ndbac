<?php

namespace Services;

use Controller;
use DB;
use Input;

/**
 * List Controller
 */
class ListController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");

    // Auth
    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }
    if (Input::get("draw")) {
      $this->getServices();
    } else if (Input::post("action") == "remove") {
      $this->remove();
    }


    // Get Services

    $this->view("services/list");
  }

  /**
   * Remove Service
   * @return void 
   */
  private function remove()
  {
    $this->resp->result = 0;

    if (!Input::post("id")) {
      $this->resp->msg = "Thiếu id";
      $this->jsonecho();
    }

    $Service = Controller::model("Service", Input::post("id"));

    if (!$Service->isAvailable()) {
      $this->resp->msg = "Dịch vụ không tồn tại!";
      $this->jsonecho();
    }

    $checkOrder = DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where("seeding_type", "=", $Service->get("idname"))
      ->select([
        DB::raw("COUNT(*) as total")
      ])
      ->get();

    if ($checkOrder[0]->total > 0) {
      $this->resp->msg = "Dịch vụ hiện đã có đơn, không thể xoá!";
      $this->jsonecho();
    }

    $Service->delete();

    $this->resp->result = 1;
    $this->jsonecho();
  }

  private function getServices()
  {
    $AuthSite = $this->getVariable("AuthSite");
    $this->resp->result = 0;
    $start = (int)Input::get("start");
    $draw = (int)Input::get("draw");
    $length = (int)Input::get("length");
    $order = Input::get("order");
    $search = Input::get("search");

    if ($draw) {
      $this->resp->draw = $draw;
    }

    $data = [];
    try {
      $query = DB::table(TABLE_PREFIX . TABLE_SERVICES);

      $search_query = trim((string)$search);
      if ($search_query) {
        $query->where(function ($q) use ($search_query) {
          $q->where(TABLE_PREFIX . TABLE_SERVICES . ".title", 'LIKE', $search_query . '%')
            ->orWhere(TABLE_PREFIX . TABLE_SERVICES . ".group", 'LIKE', $search_query . '%');
        });
      }

      if ($order && isset($order["column"]) && isset($order["dir"])) {
        $sort =  in_array($order["dir"], ["asc", "desc"]) ? $order["dir"] : "desc";
        $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
        if (in_array($column_name, ["id"])) {
          $query->orderBy(DB::raw("CAST(`" . TABLE_PREFIX . TABLE_SERVICES . "`.`" . $column_name . "` AS unsigned)"), $sort);
        } else if (strpos($column_name, ":") !== false) {
          $column_name = str_replace(":", "`.`", $column_name);
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . $column_name . "` "), $sort);
        } else if (strpos($column_name, ".") !== false) {
          $column_name = explode(".", $column_name);
          $table = array_shift($column_name);
          $path_json = [];
          foreach ($column_name as $f) {
            $path_json[] = $f;
          }
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . TABLE_SERVICES . "`.`" . $table . '`->"$.' . join(".", $path_json) . '" '), $sort);
        } else {
          $query->orderBy(DB::raw("`" . $column_name . "` "), $sort);
        }
      } else {
        $query->orderBy(TABLE_PREFIX . TABLE_SERVICES . ".id", "DESC");
      }


      $query->select([
        TABLE_PREFIX . TABLE_SERVICES . ".id",
        TABLE_PREFIX . TABLE_SERVICES . ".title",
        Db::raw(TABLE_PREFIX . TABLE_SERVICE_TITLES . ".title as title_extra"),
        TABLE_PREFIX . TABLE_SERVICES . ".is_public",
        TABLE_PREFIX . TABLE_SERVICES . ".warranty",
        TABLE_PREFIX . TABLE_SERVICES . ".is_maintaince",
        TABLE_PREFIX . TABLE_SERVICES . ".group",
        TABLE_PREFIX . TABLE_SERVICES . ".created_at",
      ]);

      $query->leftJoin(
        TABLE_PREFIX . TABLE_SERVICE_TITLES,
        function ($table) use ($AuthSite) {
          $table->on(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.service_id', '=', TABLE_PREFIX . TABLE_SERVICES . '.id');
          $table->on(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.site_id', '=', DB::raw($AuthSite->get("id")));
        }
      );

      $query->limit($length)
        ->offset($start);

      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      //$this->resp->sql = $query;
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $res;
    $this->jsonecho();
  }
}
