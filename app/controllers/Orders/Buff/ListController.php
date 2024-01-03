<?php

namespace Orders\Buff;

use Controller;
use Input;
use DateTime;
use Exception;
use DB;
use GraphQL;

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
      $Service->select('buff-' . $Route->params->service_name);
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
    } else if (Input::post("action") == "refund_bulk") {
      $this->refundBulk();
    } else if (Input::post("action") == "group_order") {
      $this->groupOrder();
    }

    $page = "buff/list";
    $view = "orders/list";

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
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where('id', "=", Input::post("id"))
        ->delete();
    } else if (Input::post("ids")) {
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
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

    $data = DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", Input::post("id"))
      ->select([
        "note_extra", "status", "id", "seeding_type"
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

    $required_fields = ["order_id", "status_edit", "seeding_type"];
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

    DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", Input::post("order_id"))
      ->update([
        "note_extra" => Input::post("note_extra") ? Input::post("note_extra") : "",
        "status" => Input::post("status_edit"),
        "seeding_type" => Input::post("seeding_type"),
        'updated_at' => date("Y-m-d H:i:s")
      ]);

    if (Input::post("status_edit") == "RUNNING") {
      DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
        ->where('order_id', "=", Input::post("order_id"))
        ->where('status', "=", 0)
        ->update(array(
          "status" => 2,
        ));
    }

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

    $required_fields = ["ids", "status_edit", "seeding_type"];
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
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->whereIn(TABLE_PREFIX . TABLE_ORDERS . ".id", Input::post("ids"))
        ->update([
          "note_extra" => Input::post("note_extra") ? Input::post("note_extra") : "",
          "status" => Input::post("status_edit"),
          "seeding_type" => Input::post("seeding_type"),
          'updated_at' => date("Y-m-d H:i:s")
        ]);

      if (Input::post("status_edit") == "RUNNING") {
        DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
          ->whereIn("order_id", Input::post("ids"))
          ->where('status', "=", 0)
          ->update(array(
            "status" => 2,
          ));
      }
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
      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->leftJoin(
          TABLE_PREFIX . TABLE_USERS,
          TABLE_PREFIX . TABLE_ORDERS . ".user_id",
          "=",
          TABLE_PREFIX . TABLE_USERS . ".id"
        )
        ->leftJoin(
          TABLE_PREFIX . TABLE_SITES,
          TABLE_PREFIX . TABLE_USERS . ".site_id",
          "=",
          TABLE_PREFIX . TABLE_SITES . ".id"
        )
        ->where(TABLE_PREFIX . TABLE_ORDERS . ".seeding_type", "=", $Service->get("idname"));

      if (in_array($status, $order_status)) {
        $query->where(TABLE_PREFIX . TABLE_ORDERS . ".status", "=", $status);
      }

      if (!$AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }

      if ($AuthUser->isAdmin() && $site_id) {
        $query->where(TABLE_PREFIX . TABLE_SITES . ".id", "=", $site_id);
      }

      if ($server_id) {
        $query->where(TABLE_PREFIX . TABLE_ORDERS . ".source_from", "=", $server_id);
      }

      $search_query = trim((string)$search);
      if ($search_query) {
        $query->where(function ($q) use ($search_query, $AuthUser) {
          $q->where(TABLE_PREFIX . TABLE_ORDERS . ".seeding_uid", '=', $search_query)
            ->orWhere(TABLE_PREFIX . TABLE_ORDERS . ".id", '=', $search_query)
            ->orWhere(TABLE_PREFIX . TABLE_ORDERS . ".group_id", '=', $search_query)
            ->orWhere(TABLE_PREFIX . TABLE_ORDERS . ".note", '=', $search_query)
            ->orWhere(TABLE_PREFIX . TABLE_ORDERS . ".note_extra", '=', $search_query);

          if ($AuthUser->isAdmin()) {
            $q->orWhere(TABLE_PREFIX . TABLE_USERS . ".email", "=", $search_query);
          }
        });
      }

      $query->select([DB::raw("COUNT(" . TABLE_PREFIX . TABLE_ORDERS . ".id) as total")]);
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

    $orderBy = TABLE_PREFIX . TABLE_ORDERS . ".id DESC";
    if ($order && isset($order["column"]) && isset($order["dir"])) {
      $sort =  in_array($order["dir"], ["asc", "desc"]) ? $order["dir"] : "desc";
      $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";

      if (in_array($column_name, ["id"])) {
        $orderBy = "CAST(`" . TABLE_PREFIX . TABLE_ORDERS . "`.`" . $column_name . "` AS unsigned) " . $sort;
      } else if (strpos($column_name, ":") !== false) {
        $column_name = str_replace(":", "`.`", $column_name);
        $orderBy = "`" . TABLE_PREFIX . $column_name . "` " . $sort;
      } else if (strpos($column_name, ".") !== false) {
        $column_name = explode(".", $column_name);
        $table = array_shift($column_name);
        $path_json = [];
        foreach ($column_name as $f) {
          $path_json[] = $f;
        }
        $orderBy = "`" . TABLE_PREFIX . TABLE_ORDERS . "`.`" . $table . '`->"$.' . join(".", $path_json) . '" ' . $sort;
      } else {
        $orderBy = "`" . $column_name . "` " . $sort;
      }

      $orderBy = TABLE_PREFIX . TABLE_ORDERS . ".id " . $sort;
    }

    $select = [
      TABLE_PREFIX . TABLE_ORDERS . ".id",
      TABLE_PREFIX . TABLE_ORDERS . ".seeding_uid",
      TABLE_PREFIX . TABLE_ORDERS . ".order_amount",
      TABLE_PREFIX . TABLE_ORDERS . ".start_num",
      TABLE_PREFIX . TABLE_ORDERS . ".reaction_type",
      // TABLE_PREFIX . TABLE_ORDERS . ".seeding_num",
      TABLE_PREFIX . TABLE_ORDERS . ".status",
      TABLE_PREFIX . TABLE_ORDERS . ".group_id",
      TABLE_PREFIX . TABLE_ORDERS . ".note",
      TABLE_PREFIX . TABLE_USERS . ".email",
      TABLE_PREFIX . TABLE_ORDERS . ".note_extra",
      TABLE_PREFIX . TABLE_ORDERS . ".created_at",
      TABLE_PREFIX . TABLE_ORDERS . ".expired_warranty_at",
    ];


    $select[] = "COUNT(" . TABLE_PREFIX . TABLE_ORDER_LOGS . ".id) as seeding_num";
    $query->leftJoin(TABLE_PREFIX . TABLE_ORDER_LOGS, function ($table) {
      $table->on(
        TABLE_PREFIX . TABLE_ORDER_LOGS . ".order_id",
        "=",
        TABLE_PREFIX . TABLE_ORDERS . ".id"
      );
      $table->on(TABLE_PREFIX . TABLE_ORDER_LOGS . ".status", '=', DB::raw(1));
    })
      ->groupBy(TABLE_PREFIX . TABLE_ORDERS . ".id");

    $sql = $query->getQuery()->getRawSql();
    $sql = str_replace("COUNT(" . TABLE_PREFIX . TABLE_ORDERS . ".id) as total", join(", ", $select), $sql);
    $query = $sql . " ORDER BY " . $orderBy . " LIMIT " . intval($start) . ", " . intval($length);
    //$this->resp->sql = $query;
    // print_r($query);
    try {
      $res = DB::query($query)->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $res;
    $this->jsonecho();
  }

  private function groupOrder()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $Service = $this->getVariable("Service");

    $required_fields = ["ids", "title"];
    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->jsonecho();
      }
    }

    if (!is_array(Input::post("ids"))) {
      $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
      $this->jsonecho();
    }

    $group_id = strtoupper(substr(Input::post("title"), 0, 10));

    $countOrders = DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "!=", $AuthUser->get("id"))
      ->where(TABLE_PREFIX . TABLE_ORDERS . ".group_id", "=", $group_id)
      ->select([
        DB::raw("COUNT(*) as total")
      ])
      ->get();

    if ($countOrders[0]->total > 0) {
      $this->resp->msg = "Mã nhóm đã tồn tại.";
      $this->jsonecho();
    }

    $infoOrders = DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "=", $AuthUser->get("id"))
      ->whereIn(TABLE_PREFIX . TABLE_ORDERS . ".id", Input::post("ids"))
      ->select([
        DB::raw("SUM(order_amount) as total")
      ])
      ->get();

    if (count($infoOrders) == 0) {
      $this->resp->msg = "Danh sách order không tồn tại!";
      $this->jsonecho();
    }

    $sumAmountOrders = DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "=", $AuthUser->get("id"))
      ->where(TABLE_PREFIX . TABLE_ORDERS . ".group_id", "=", $group_id)
      ->select([
        DB::raw("SUM(" . TABLE_PREFIX . TABLE_ORDERS . ".order_amount) as total")
      ])
      ->get();

    if ($sumAmountOrders[0]->total + $infoOrders[0]->total > get_option("MAX_NUM_AMOUNT_GROUP_ORDER")) {
      $this->resp->msg = "Tổng số mua các đơn trong 1 nhóm không được quá " . number_format(get_option("MAX_NUM_AMOUNT_GROUP_ORDER"));
      $this->jsonecho();
    }

    DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "=", $AuthUser->get("id"))
      ->where(TABLE_PREFIX . TABLE_ORDERS . ".seeding_type", "=", $Service->get("idname"))
      ->whereIn(TABLE_PREFIX . TABLE_ORDERS . ".id", Input::post("ids"))
      ->update([
        "group_id" => $group_id
      ]);

    $this->resp->result = 1;
    $this->resp->reload_table = 1;
    $this->resp->msg = "Gộp đơn thành công";
    $this->jsonecho();
  }

  private function refundBulk()
  {
    $this->resp->result = 0;

    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");

    if (!$AuthUser->isAdmin()) {
      $this->resp->msg = "Tính năng này chỉ cho admin!";
      $this->jsonecho();
    }

    $ids = Input::post("ids");

    if (!$ids) {
      $this->resp->msg = "Thiếu order_id";
      $this->jsonecho();
    }
    foreach ($ids as $order_id) {
      try {
        $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
          ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", $order_id)
          ->select([
            TABLE_PREFIX . TABLE_ORDERS . ".id",
            TABLE_PREFIX . TABLE_ORDERS . ".status",
            TABLE_PREFIX . TABLE_ORDERS . ".user_id",
            TABLE_PREFIX . TABLE_ORDERS . ".created_at",
            TABLE_PREFIX . TABLE_ORDERS . ".price",
            TABLE_PREFIX . TABLE_ORDERS . ".__typename",
            TABLE_PREFIX . TABLE_ORDERS . ".start_num",
            TABLE_PREFIX . TABLE_ORDERS . ".seeding_num",
            TABLE_PREFIX . TABLE_ORDERS . ".order_amount",
            TABLE_PREFIX . TABLE_ORDERS . ".wwwURL",
            TABLE_PREFIX . TABLE_ORDERS . ".uid_post",
            TABLE_PREFIX . TABLE_ORDERS . ".source_from",
            TABLE_PREFIX . TABLE_ORDERS . ".seeding_uid",
            TABLE_PREFIX . TABLE_ORDERS . ".seeding_type",
          ]);

        if (!$AuthUser->isAdmin()) {
          $query->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "=", $AuthUser->get("id"));
        }
        $res = $query->get();
      } catch (\Exception $ex) {
        $this->resp->msg = $ex->getMessage();
        $this->jsonecho();
      }

      if (count($res) == 0) {
        $this->resp->msg = "Order không tồn tại";
        $this->jsonecho();
      }

      $order = $res[0];

      $Server = Controller::model("Server", $order->source_from);
      if ($Server->isAvailable() && !$Server->get("allow_refund")) {
        $this->resp->msg = $Server->get("name") . " không cho phép hoàn đơn.";
        $this->jsonecho();
      }

      if ($order->status == 'REFUND') {
        $this->resp->msg = "Đơn đã hoàn";
        $this->jsonecho();
      } else if (!in_array($order->status, ['RUNNING', 'PENDING', 'PAUSED', 'HOLDING', 'ERROR'])) {
        $this->resp->msg = "Không thể hoàn với đơn chưa running";
        $this->jsonecho();
      }

      $pdo = DB::pdo();
      $pdo->beginTransaction();
      try {
        if ($AuthUser->get("id") != $order->user_id) {
          $User = Controller::model("User", $order->user_id);
        } else {
          $User = $AuthUser;
        }

        if ($order->status == "PENDING") {
          $refund = $order->price;
          $real_seeding_num = 0;
        } else {
          $refund = 0;
          // thuat toan refund
          $start = intval($order->start_num);
          $order_amount = intval($order->order_amount);

          $checkLog = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
            ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".order_id", "=", $order_id)
            ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".status", "=", 1)
            ->select([
              DB::raw("COUNT(" . TABLE_PREFIX . TABLE_ORDER_LOGS . ".id) as total_log"),
            ])
            ->get();
          $real_seeding_num = intval($checkLog[0]->total_log);

          // bắt đầu xử lý
          $remain = $order_amount - $real_seeding_num;
          if ($remain > 0) {
            $refund = (int)(($order->price * $remain) / $order_amount);
          }
        }

        DB::table(TABLE_PREFIX . TABLE_ORDERS)
          ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", $order_id)
          ->update([
            'status' => 'REFUND',
            'seeding_num' => $real_seeding_num,
            'updated_at' => date("Y-m-d H:i:s")
          ]);


        $current = $User->get("balance");
        $balance = $User->get("balance") + $refund;

        if ($AuthUser->get("id") == $User->get("id") || !$User->isAdmin()) {
          DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
            ->insert(array(
              "user_id" => $User->get("id"),
              "before" => $current,
              "money" => $refund,
              "type" => "+",
              "after" => $balance,
              "type_code" => "REFUND_ORDER",
              "seeding_type" => $order->seeding_type,
              "seeding_uid" => $order->seeding_uid,
              "site_id" => $User->get("site_id"),
              "content" => "Hoàn đơn thành công #REFUND_ORDER_" . $order_id,
              'date' => date("Y-m-d H:i:s")
            ));
        } else {
          DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
            ->insert(array(
              "user_id" => $User->get("id"),
              "before" => $current,
              "money" => $refund,
              "type" => "+",
              "after" => $balance,
              "type_code" => "ADMIN_REFUND_ORDER",
              "seeding_type" => $order->seeding_type,
              "seeding_uid" => $order->seeding_uid,
              "site_id" => $User->get("site_id"),
              "content" => "Hoàn đơn thành công #ADMIN_REFUND_ORDER_" . $order_id,
              'date' => date("Y-m-d H:i:s")
            ));
        }

        $User->set("balance", $balance)->save();
        $pdo->commit();
      } catch (\Exception $ex) {
        $pdo->rollback();
        $this->resp->msg = "Lỗi hệ thống! Hãy thử lại. ";
        $this->resp->error = $ex->getMessage();
        $this->jsonecho();
      }
    }



    $this->resp->result = 1;
    $this->resp->msg = "Hoàn tiền đơn thành công";
    $this->jsonecho();
  }
}
