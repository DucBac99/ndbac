<?php

namespace Users;

use Firebase\JWT\JWT;
use Controller;
use DB;
use Input;
use Exception;

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
      $this->getUsers();
    } else if (Input::post("action") == "remove") {
      $this->remove();
    } else if (Input::post("action") == "change_balance") {
      $this->change_balance();
    } else if (Input::post("action") == "change_analytics") {
      $this->change_analytics();
    } else if (Input::post("action") == "gen_qr") {
      $this->genQR();
    }

    $Sites = Controller::model("Sites");
    $Sites->where("id", ">", 0)
      ->fetchData();
    $this->setVariable("Sites", $Sites);

    $this->view("users/list");
  }


  /**
   * Remove User
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

    $User = Controller::model("User", Input::post("id"));

    if (!$User->isAvailable()) {
      $this->resp->msg = "Người dùng không tồn tại!";
      $this->jsonecho();
    }

    if ($AuthUser->get("id") == $User->get("id")) {
      $this->resp->msg = "Bạn không thể xóa tài khoản của chính mình!";
      $this->jsonecho();
    }

    $User->delete();

    $this->resp->result = 1;
    $this->jsonecho();
  }

  private function change_balance()
  {
    $this->resp->result = 0;
    $required_fields = ["money", "type_change", "user_id"];

    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->jsonecho();
      }
    }

    $User = Controller::model("User", Input::post("user_id"));

    if (!$User->isAvailable()) {
      $this->resp->msg = "Người dùng không tồn tại!";
      $this->jsonecho();
    }

    if (intval(Input::post("money")) < 0) {
      $this->resp->msg = "Số tiền không hợp lệ!";
      $this->jsonecho();
    }

    $pdo = DB::pdo();
    $pdo->beginTransaction();
    try {
      $total = intval(Input::post("money"));
      $type_change = Input::post("type_change");
      $current = $User->get("balance");
      $current_deposit = $User->get("total_deposit");
      if ($type_change == "-") {
        $after = $current - $total;
        $after_deposit = $current_deposit - $total;
      } else if ($type_change == "+") {
        $after = $current + $total;
        $after_deposit = $current_deposit + $total;
      } else {
        $this->resp->msg = "Loại không hợp lệ";
        $this->jsonecho();
      }

      if (Input::post("change_deposit")) {
        $User->set("total_deposit", $after_deposit);
      }

      if (Input::post("reference_id") && $type_change == "+") {
        $User->set("total_deposit", $after_deposit);

        $Payment = Controller::model("Payment", Input::post("reference_id"));
        $Payment->set("user_id", $User->get("id"))
          ->set("data", 'Admin cộng thủ công')
          ->set("status", "paid")
          ->set("payment_gateway", "vietcombank")
          ->set("payment_id", Input::post("reference_id"))
          ->set("total", $total)
          ->set("site_id", $User->get("site_id"))
          ->set("currency", "VND")
          ->save();
      }
      $User->set("balance", $after)->save();

      DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->insert(array(
          "user_id" => $User->get("id"),
          "before" => $current,
          "money" => $total,
          "type" => $type_change,
          "after" => $after,
          "site_id" => $User->get("site_id"),
          "seeding_type" => $type_change == "+" ? "ADD_BA_ADMIN" : "SUB_BA_ADMIN",
          "seeding_uid" => $User->get("id"),
          "type_code" => $type_change == "+" ? "ADD_BALANCE_ADMIN" : "SUBTRACT_BALANCE_ADMIN",
          "content" => $type_change == "+" ? "Admin cộng tiền vào số dư tài khoản" : "Admin trừ tiền số dư tài khoản",
          'date' => date("Y-m-d H:i:s")
        ));
      $pdo->commit();
    } catch (\Exception $ex) {
      $pdo->rollback();
      $this->resp->msg = "Lỗi hệ thống! Hãy thử lại. ";
      $this->resp->error = $ex->getMessage();
      $this->jsonecho();
    }



    $this->resp->result = 1;
    $this->resp->reload_table = 1;
    $this->resp->msg = "Thay đổi thành công";
    $this->jsonecho();
  }

  private function change_analytics()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    if (!is_array(Input::post("ids"))) {
      $this->resp->msg = "Thiếu id";
      $this->jsonecho();
    }

    if (count(Input::post("ids")) > 100) {
      $this->resp->msg = "Số lượng quá lớn!";
      $this->jsonecho();
    }

    DB::table(TABLE_PREFIX . TABLE_USERS)
      ->whereIn('id', Input::post("ids"))
      ->update(array(
        "has_analytics" => Input::post("has-analytics") ? 1 : 0
      ));

    $this->resp->result = 1;
    $this->resp->msg = "Cập nhật thành công";
    $this->jsonecho();
  }

  private function genQR()
  {
    $this->resp->result = 0;

    if (!Input::post("id")) {
      $this->resp->msg = "Thiếu id";
      $this->jsonecho();
    }

    $User = Controller::model("User", Input::post("id"));
    if (!$User->isAvailable()) {
      $this->resp->msg = "Người dùng không tồn tại!";
      $this->jsonecho();
    }

    $created_at = time();
    $payload = [
      'iat' => $created_at,
      "id" => (int) $User->get("id"),
      "password" => md5($User->get("email")),
    ];
    $accessToken = JWT::encode($payload, NP_SALT, 'HS256');

    $url = urlencode(APPURL . "/login?" . http_build_query(array(
      "action" => "login_token",
      "accessToken" => $accessToken
    )));
    $this->resp->result = 1;
    $this->resp->url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" . $url . "&choe=UTF-8";
    $this->resp->msg = "Tạo mã đăng nhập thành công";
    $this->resp->email = $User->get("email");
    $this->jsonecho();
  }

  /** 
   * Get Users
   * @return void
   */
  private function getUsers()
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
      $query = DB::table(TABLE_PREFIX . TABLE_USERS)
        ->leftJoin(
          TABLE_PREFIX . TABLE_ROLES,
          TABLE_PREFIX . TABLE_USERS . ".role_id",
          "=",
          TABLE_PREFIX . TABLE_ROLES . ".id"
        )
        ->leftJoin(
          TABLE_PREFIX . TABLE_SITES,
          TABLE_PREFIX . TABLE_USERS . ".site_id",
          "=",
          TABLE_PREFIX . TABLE_SITES . ".id"
        );

      if ($site_id) {
        $query->where(TABLE_PREFIX . TABLE_USERS . ".site_id", "=", $site_id);
      } else {
        $query->where(TABLE_PREFIX . TABLE_USERS . ".role_id", "<", 3);
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
          $query->orderBy(DB::raw("CAST(`" . TABLE_PREFIX . TABLE_USERS . "`.`" . $column_name . "` AS unsigned)"), $sort);
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
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . TABLE_USERS . "`.`" . $table . '`->"$.' . join(".", $path_json) . '" '), $sort);
        } else {
          $query->orderBy(DB::raw("`" . $column_name . "` "), $sort);
        }
      } else {
        $query->orderBy(TABLE_PREFIX . TABLE_USERS . ".id", "DESC");
      }


      $query->select([
        TABLE_PREFIX . TABLE_USERS . ".id",
        TABLE_PREFIX . TABLE_USERS . ".email",
        TABLE_PREFIX . TABLE_USERS . ".firstname",
        TABLE_PREFIX . TABLE_USERS . ".role_id",
        TABLE_PREFIX . TABLE_USERS . ".lastname",
        TABLE_PREFIX . TABLE_USERS . ".is_active",
        TABLE_PREFIX . TABLE_USERS . ".balance",
        TABLE_PREFIX . TABLE_USERS . ".total_deposit",
        TABLE_PREFIX . TABLE_USERS . ".created_at",
        TABLE_PREFIX . TABLE_SITES . ".domain",
        TABLE_PREFIX . TABLE_ROLES . ".title",
        TABLE_PREFIX . TABLE_ROLES . ".color",
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
