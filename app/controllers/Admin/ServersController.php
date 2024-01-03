<?php

namespace Admin;

use Input;
use Controller;
use DB;
use Exception;

/**
 * Servers Controller
 */
class ServersController extends Controller
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
      $this->getServers();
    } else if (Input::post("action") == "remove") {
      $this->remove();
    }

    $this->view("admin/servers");
  }


  /**
   * Remove Server
   * @return void 
   */
  private function remove()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    if (!Input::post("id")) {
      $this->resp->msg = "Thiếu id";
      $this->jsonecho();
    }

    $Server = Controller::model("Server", Input::post("id"));

    if (!$Server->isAvailable()) {
      $this->resp->msg = "Người dùng không tồn tại!";
      $this->jsonecho();
    }

    if ($Server->get("id") == 1) {
      $this->resp->msg = "Không thể xoá server gốc!";
      $this->jsonecho();
    }

    $Server->delete();

    $this->resp->result = 1;
    $this->jsonecho();
  }

  /** 
   * Get Servers
   * @return void
   */
  private function getServers()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    $order = Input::get("order");
    $search = Input::get("search");
    $start = (int)Input::get("start");
    $draw = (int)Input::get("draw");
    $length = (int)Input::get("length");
    $site_id = (int)Input::get("site_id");

    if ($draw) {
      $this->resp->draw = $draw;
    }

    $data = [];

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_SERVERS);

      if ($site_id) {
        $query->where(TABLE_PREFIX . TABLE_SERVERS . ".site_id", "=", $site_id);
      }

      $search_query = trim((string)$search);
      if ($search_query) {
        $query->where(function ($q) use ($search_query) {
          $q->where(TABLE_PREFIX . TABLE_SERVERS . ".name", 'LIKE', $search_query . '%')
            ->orWhere(TABLE_PREFIX . TABLE_SERVERS . ".api_url", 'LIKE', $search_query . '%');
        });
      }

      if ($order && isset($order["column"]) && isset($order["dir"])) {
        $sort =  in_array($order["dir"], ["asc", "desc"]) ? $order["dir"] : "desc";
        $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
        if (in_array($column_name, ["id"])) {
          $query->orderBy(DB::raw("CAST(`" . TABLE_PREFIX . TABLE_SERVERS . "`.`" . $column_name . "` AS unsigned)"), $sort);
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
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . TABLE_SERVERS . "`.`" . $table . '`->"$.' . join(".", $path_json) . '" '), $sort);
        } else {
          $query->orderBy(DB::raw("`" . $column_name . "` "), $sort);
        }
      } else {
        $query->orderBy(TABLE_PREFIX . TABLE_SERVERS . ".id", "DESC");
      }
      // echo $orderBy;

      $query->select([
        TABLE_PREFIX . TABLE_SERVERS . ".id",
        TABLE_PREFIX . TABLE_SERVERS . ".name",
        TABLE_PREFIX . TABLE_SERVERS . ".api_url",
        TABLE_PREFIX . TABLE_SERVERS . ".api_key",
        TABLE_PREFIX . TABLE_SERVERS . ".created_at",
      ])->limit($length)
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
