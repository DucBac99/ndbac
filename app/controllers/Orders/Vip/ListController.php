<?php

namespace Orders\Vip;

use Controller;
use Input;
use DateTime;
use Exception;
use DB;
use stdClass;

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
    $Route = $this->getVariable("Route");

    // Auth
    if (!$AuthUser) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $Service = Controller::model("Service");
    if (isset($Route->params->service_name)) {
      $Service->select('vip-' . $Route->params->service_name);
    }

    if (!$Service->isAvailable() || !$Service->get("is_public")) {
      header("HTTP/1.0 404 Not Found");
      exit;
    }

    require_once(APPPATH . '/inc/order-status.inc.php');

    if ($AuthUser->isAdmin()) {
      $Sites = Controller::model("Sites");
      $Sites->where("id", ">", 0)
        ->fetchData();

      $this->setVariable("Sites", $Sites);
    }

    $Servers = DB::table(TABLE_PREFIX . TABLE_SERVERS)->get();

    $this->setVariable("Servers", $Servers)
      ->setVariable("Service", $Service)
      ->setVariable("order_status", $order_status);

    if (Input::get("draw")) {
      $this->getOrders();
    } else if (Input::post("action") == "remove") {
      $this->remove();
    } else if (Input::post("action") == "get_info_summary") {
      $this->get_info_summary();
    } else if (Input::post("action") == "edit") {
      $this->edit();
    } else if (Input::post("action") == "edit_bulk") {
      $this->editBulk();
    }
    $page = 'vip/list';
    $view = "orders/vip";

    $this->setVariable("page", $page)
      ->setVariable("uri", APPURL . "/orders/" . $Service->get("group") . "/" . $Service->get("idname"));
    $this->view($view);
  }

  /**
   * Remove Order
   * @return void 
   */
  private function remove()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    if (!$AuthUser->isAdmin()) {
      $this->resp->msg = "Tính năng này chỉ cho admin!";
      $this->jsonecho();
    }

    if (Input::post("id") && !is_numeric(Input::post("id"))) {
      $this->resp->msg = "Thiếu id";
      $this->jsonecho();
    }

    if (Input::post("ids")) {
      if (!is_array(Input::post("ids"))) {
        $this->resp->msg = "Thiếu id";
        $this->jsonecho();
      }
      if (count(Input::post("ids")) > 100) {
        $this->resp->msg = "Số lượng quá lớn!";
        $this->jsonecho();
      }
    }


    if (Input::post("id")) {
      DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->where('id', "=", Input::post("id"))
        ->delete();
    } else if (Input::post("ids")) {
      DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->whereIn('id', Input::post("ids"))
        ->delete();
    }

    $this->resp->result = 1;
    $this->resp->msg = "Xoá thành công";
    $this->jsonecho();
  }

  private function get_info_summary()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    if (!$AuthUser->isAdmin()) {
      $this->resp->msg = "Tính năng này chỉ cho admin!";
      $this->jsonecho();
    }

    if (!Input::post("id")) {
      $this->resp->msg = "Thiếu id";
      $this->jsonecho();
    }

    $data = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
      ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", "=", Input::post("id"))
      ->select([
        "note_extra", "status", "id"
      ])
      ->get();

    if (count($data) == 0) {
      $this->resp->msg = "ORDER ID không tồn tại!";
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $data[0];
    $this->jsonecho();
  }

  private function edit()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $order_status = $this->getVariable("order_status");

    if (!$AuthUser->isAdmin()) {
      $this->resp->msg = "Tính năng này chỉ cho admin!";
      $this->jsonecho();
    }

    $required_fields = ["order_id", "status_edit"];
    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->resp->novalidate = true;
        $this->jsonecho();
      }
    }

    if (!in_array(Input::post("status_edit"), $order_status)) {
      $this->resp->msg = "Status không hợp lệ";
      $this->resp->novalidate = true;
      $this->jsonecho();
    }

    DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
      ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", "=", Input::post("order_id"))
      ->update([
        "note_extra" => Input::post("note_extra") ? Input::post("note_extra") : "",
        "status" => Input::post("status_edit"),
        'updated_at' => date("Y-m-d H:i:s")
      ]);

    $this->resp->result = 1;
    $this->resp->reload_table = 1;
    $this->resp->msg = "Cập nhật thành công";
    $this->jsonecho();
  }

  private function editBulk()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $order_status = $this->getVariable("order_status");

    if (!$AuthUser->isAdmin()) {
      $this->resp->msg = "Tính năng này chỉ cho admin!";
      $this->jsonecho();
    }

    $required_fields = ["ids", "status_edit"];
    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->resp->novalidate = true;
        $this->jsonecho();
      }
    }

    if (!is_array(Input::post("ids"))) {
      $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
      $this->jsonecho();
    }

    if (!in_array(Input::post("status_edit"), $order_status)) {
      $this->resp->msg = "Status không hợp lệ";
      $this->resp->novalidate = true;
      $this->jsonecho();
    }

    try {
      DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->whereIn(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", Input::post("ids"))
        ->update([
          "note_extra" => Input::post("note_extra") ? Input::post("note_extra") : "",
          "status" => Input::post("status_edit"),
          'updated_at' => date("Y-m-d H:i:s")
        ]);
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->reload_table = 1;
    $this->resp->msg = "Cập nhật thành công";
    $this->jsonecho();
  }

  /** 
   * Get Orders
   * @return void
   */
  private function getOrders()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $Service = $this->getVariable("Service");
    $order_status = $this->getVariable("order_status");

    $order = Input::get("order");
    $search = Input::get("search");
    $start = (int)Input::get("start");
    $draw = (int)Input::get("draw");
    $length = (int)Input::get("length");
    $status = Input::get("status");
    $site_id = (int)Input::get("site_id");
    $server_id = (int)Input::get("server_id");

    if ($draw) {
      $this->resp->draw = $draw;
    }

    $data = [];

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->leftJoin(
          TABLE_PREFIX . TABLE_USERS,
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id",
          "=",
          TABLE_PREFIX . TABLE_USERS . ".id"
        )
        ->leftJoin(
          TABLE_PREFIX . TABLE_SITES,
          TABLE_PREFIX . TABLE_USERS . ".site_id",
          "=",
          TABLE_PREFIX . TABLE_SITES . ".id"
        )
        ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".seeding_type", "=", $Service->get("idname"));

      if (in_array($status, $order_status)) {
        $query->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".status", "=", $status);
      }

      if (!$AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }

      if ($AuthUser->isAdmin() && $site_id) {
        $query->where(TABLE_PREFIX . TABLE_SITES . ".id", "=", $site_id);
      }

      if ($server_id) {
        $query->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".source_from", "=", $server_id);
      }

      $search_query = trim((string)$search);
      if ($search_query) {
        $query->where(function ($q) use ($search_query, $AuthUser) {
          $q->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".seeding_uid", '=', $search_query)
            ->orWhere(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", '=', $search_query)
            ->orWhere(TABLE_PREFIX . TABLE_VIP_ORDERS . ".group_id", '=', $search_query)
            ->orWhere(TABLE_PREFIX . TABLE_VIP_ORDERS . ".note", '=', $search_query)
            ->orWhere(TABLE_PREFIX . TABLE_VIP_ORDERS . ".note_extra", '=', $search_query);

          if ($AuthUser->isAdmin()) {
            $q->orWhere(TABLE_PREFIX . TABLE_USERS . ".email", "=", $search_query);
          }
        });
      }

      $query->select([DB::raw("COUNT(" . TABLE_PREFIX . TABLE_VIP_ORDERS . ".id) as total")]);
      $res = $query->get();
      $total = (int)$res[0]->total;
      $this->resp->summary = array(
        "total_count" => $total
      );
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    // print_r($res);

    if ($total == 0) {
      $this->resp->result = 1;
      $this->resp->data = $data;
      $this->jsonecho();
    }

    $orderBy = " ORDER BY " . TABLE_PREFIX . TABLE_VIP_ORDERS . ".id DESC";
    if ($order && isset($order["column"]) && isset($order["dir"])) {
      $sort =  in_array($order["dir"], ["asc", "desc"]) ? $order["dir"] : "desc";
      $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";

      if (in_array($column_name, ["id"])) {
        $orderBy = " ORDER BY CAST(`" . TABLE_PREFIX . TABLE_VIP_ORDERS . "`.`" . $column_name . "` AS unsigned) " . $sort;
      } else if (strpos($column_name, ":") !== false) {
        $column_name = str_replace(":", "`.`", $column_name);
        $orderBy = " ORDER BY `" . TABLE_PREFIX . $column_name . "` " . $sort;
      } else if (strpos($column_name, ".") !== false) {
        $column_name = explode(".", $column_name);
        $table = array_shift($column_name);
        $path_json = [];
        foreach ($column_name as $f) {
          $path_json[] = $f;
        }
        $orderBy = " ORDER BY `" . TABLE_PREFIX . TABLE_VIP_ORDERS . "`.`" . $table . '`->"$.' . join(".", $path_json) . '" ' . $sort;
      } else {
        $orderBy = " ORDER BY `" . $column_name . "` " . $sort;
      }
    }

    $select = [
      TABLE_PREFIX . TABLE_VIP_ORDERS . ".id",
      TABLE_PREFIX . TABLE_VIP_ORDERS . ".seeding_uid",
      TABLE_PREFIX . TABLE_VIP_ORDERS . ".month",
      TABLE_PREFIX . TABLE_VIP_ORDERS . ".order_amount",
      TABLE_PREFIX . TABLE_VIP_ORDERS . ".status",
      TABLE_PREFIX . TABLE_VIP_ORDERS . ".note",
      TABLE_PREFIX . TABLE_USERS . ".email",
      TABLE_PREFIX . TABLE_VIP_ORDERS . ".note_extra",
      TABLE_PREFIX . TABLE_VIP_ORDERS . ".updated_at",
      TABLE_PREFIX . TABLE_VIP_ORDERS . ".expired_at",
    ];

    $sql = $query->getQuery()->getRawSql();
    $sql = str_replace("COUNT(" . TABLE_PREFIX . TABLE_VIP_ORDERS . ".id) as total", join(", ", $select), $sql);
    $query = $sql . $orderBy . " LIMIT " . intval($start) . ", " . intval($length);
    // print_r($query);
    try {
      $res = DB::query($query)->get();
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
