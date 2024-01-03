<?php

namespace Profile;

use Controller;
use Input;
use DB;

class PaymentHistoryController extends Controller
{
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");


    if (!$AuthUser) {
      header("Location: " . APPURL . "/login");
      exit;
    }


    if ($AuthUser->isAdmin()) {
      $Sites = Controller::model("Sites");
      $Sites->where("id", ">", 0)
        ->fetchData();
      $this->setVariable("Sites", $Sites);
    }

    // Set variables
    if (Input::get("draw")) {
      $this->getPayments();
    }

    $this->view("profile/payment-history");
  }

  private function getPayments()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");

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
      $query = DB::table(TABLE_PREFIX . TABLE_PAYMENTS)
        ->leftJoin(
          TABLE_PREFIX . TABLE_USERS,
          TABLE_PREFIX . TABLE_PAYMENTS . ".user_id",
          "=",
          TABLE_PREFIX . TABLE_USERS . ".id"
        )
        ->leftJoin(
          TABLE_PREFIX . TABLE_SITES,
          TABLE_PREFIX . TABLE_USERS . ".site_id",
          "=",
          TABLE_PREFIX . TABLE_SITES . ".id"
        );

      if (!$site_id) {
        $site_id = $AuthSite->get("id");
      }
      $query->where(TABLE_PREFIX . TABLE_PAYMENTS . ".site_id", "=", $site_id);
      if (!$AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_PAYMENTS . ".user_id", "=", $AuthUser->get("id"));
      }

      $search_query = trim((string)$search);
      if ($search_query) {
        $query->where(function ($q) use ($search_query) {
          $q->where(TABLE_PREFIX . TABLE_USERS . ".firstname", 'LIKE', $search_query . '%')
            ->orWhere(TABLE_PREFIX . TABLE_USERS . ".lastname", 'LIKE', $search_query . '%')
            ->orWhere(TABLE_PREFIX . TABLE_USERS . ".email", 'LIKE', $search_query . '%');
        });
      }

      if ($order && isset($order["column"]) && isset($order["dir"])) {
        $sort =  in_array($order["dir"], ["asc", "desc"]) ? $order["dir"] : "desc";
        $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
        if (in_array($column_name, ["id"])) {
          $query->orderBy(DB::raw("CAST(`" . TABLE_PREFIX . TABLE_PAYMENTS . "`.`" . $column_name . "` AS unsigned)"), $sort);
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
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . TABLE_PAYMENTS . "`.`" . $table . '`->"$.' . join(".", $path_json) . '" '), $sort);
        } else {
          $query->orderBy(DB::raw("`" . $column_name . "` "), $sort);
        }
      } else {
        $query->orderBy(TABLE_PREFIX . TABLE_PAYMENTS . ".id", "DESC");
      }

      $query->select([
        TABLE_PREFIX . TABLE_PAYMENTS . ".id",
        TABLE_PREFIX . TABLE_PAYMENTS . ".data",
        TABLE_PREFIX . TABLE_PAYMENTS . ".status",
        TABLE_PREFIX . TABLE_PAYMENTS . ".payment_gateway",
        TABLE_PREFIX . TABLE_PAYMENTS . ".payment_id",
        TABLE_PREFIX . TABLE_PAYMENTS . ".total",
        TABLE_PREFIX . TABLE_PAYMENTS . ".currency",
        TABLE_PREFIX . TABLE_PAYMENTS . ".date",
        TABLE_PREFIX . TABLE_SITES . ".domain",
        TABLE_PREFIX . TABLE_USERS . ".email",
        TABLE_PREFIX . TABLE_USERS . ".firstname",
        TABLE_PREFIX . TABLE_USERS . ".lastname",
      ])
      ->limit($length)
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
