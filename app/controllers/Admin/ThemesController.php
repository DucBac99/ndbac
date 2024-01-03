<?php

namespace Admin;

use Input;
use Controller;
use DB;
use Exception;

/**
 * Themes Controller
 */
class ThemesController extends Controller
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

    $this->setVariable("page", "themes");
    if (Input::get("draw")) {
      $this->getThemes();
    } else if (Input::post("action") == "remove") {
      $this->remove();
    }

    $this->view("admin/themes");
  }


  /**
   * Remove Theme
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

    $Theme = Controller::model("Theme", Input::post("id"));

    if (!$Theme->isAvailable()) {
      $this->resp->msg = "Theme không tồn tại!";
      $this->jsonecho();
    }

    $checkSite = DB::table(TABLE_PREFIX . TABLE_SITES)
      ->where("theme", "=", $Theme->get("idname"))
      ->select([
        DB::raw("COUNT(*) as total")
      ])
      ->get();

    if ($checkSite[0]->total > 0) {
      $this->resp->msg = "Theme đã có site dùng, không thể xoá!";
      $this->jsonecho();
    }

    $Theme->delete();

    $this->resp->result = 1;
    $this->jsonecho();
  }

  /** 
   * Get Themes
   * @return void
   */
  private function getThemes()
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
      $query = DB::table(TABLE_PREFIX . TABLE_THEMES);

      if ($site_id) {
        $query->where(TABLE_PREFIX . TABLE_THEMES . ".site_id", "=", $site_id);
      }

      $search_query = trim((string)$search);
      if ($search_query) {
        $query->where(function ($q) use ($search_query) {
          $q->where(TABLE_PREFIX . TABLE_THEMES . ".idname", 'LIKE', $search_query . '%');
        });
      }
      
      if ($order && isset($order["column"]) && isset($order["dir"])) {
        $sort =  in_array($order["dir"], ["asc", "desc"]) ? $order["dir"] : "desc";
        $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
        if (in_array($column_name, ["id"])) {
          $query->orderBy(DB::raw("CAST(`" . TABLE_PREFIX . TABLE_THEMES . "`.`" . $column_name . "` AS unsigned)"), $sort);
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
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . TABLE_THEMES . "`.`" . $table . '`->"$.' . join(".", $path_json) . '" '), $sort);
        } else {
          $query->orderBy(DB::raw("`" . $column_name . "` "), $sort);
        }
      } else {
        $query->orderBy(TABLE_PREFIX . TABLE_THEMES . ".id", "DESC");
      }


      $query->select([
        TABLE_PREFIX . TABLE_THEMES . ".id",
        TABLE_PREFIX . TABLE_THEMES . ".idname",
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
