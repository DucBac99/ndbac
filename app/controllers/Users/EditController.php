<?php

namespace Users;

use Controller;
use DB;
use Input;
use Exception;

/**
 * Edit Controller
 */
class EditController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $Route = $this->getVariable("Route");
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");

    // Auth
    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }


    $User = Controller::model("User");
    if (isset($Route->params->id)) {
      $User->select($Route->params->id);

      if (!$User->isAvailable()) {
        header("Location: " . APPURL . "/users");
        exit;
      }
    }

    $Roles = Controller::model("Roles");
    $Roles->where("site_id", "=", $AuthSite->get("id"))
      ->fetchData();

    $this->setVariable("Roles", $Roles)
      ->setVariable("User", $User);

    if (Input::post("action") == "save") {
      $this->save();
    }

    $this->view("users/edit");
  }


  /**
   * Save (new|edit) user
   * @return void 
   */
  private function save()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $User = $this->getVariable("User");
    $AuthSite = $this->getVariable("AuthSite");

    // Check if this is new or not
    $is_new = !$User->isAvailable();

    // Check required fields
    $required_fields = ["firstname", "lastname"];
    if ($is_new) {
      $required_fields[] = "email";
      $required_fields[] = "password";
      $required_fields[] = "password-confirm";
    }

    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->jsonecho();
      }
    }

    if ($is_new) {
      // Check email
      if (!filter_var(Input::post("email"), FILTER_VALIDATE_EMAIL)) {
        $this->resp->msg = "Email không hợp lệ.";
        $this->jsonecho();
      }

      $u = Controller::model("User", Input::post("email"));
      if ($u->isAvailable() && $u->get("id") != $User->get("id")) {
        $this->resp->msg = "Email không khả dụng.";
        $this->jsonecho();
      }
    }



    // Check pass.
    if (mb_strlen(Input::post("password")) > 0) {
      if (mb_strlen(Input::post("password")) < 6) {
        $this->resp->msg = "Mật khẩu phải dài ít nhất 6 ký tự!";
        $this->jsonecho();
      }

      if (Input::post("password-confirm") != Input::post("password")) {
        $this->resp->msg = "Xác nhận mật khẩu không khớp!";
        $this->jsonecho();
      }
    }

    // Start setting data
    $User->set("firstname", Input::post("firstname"))
      ->set("lastname", Input::post("lastname"))
      ->set("has_analytics", Input::post("has-analytics") ? 1 : 0)
      ->set("is_viewer", Input::post("is-viewer") ? 1 : 0);

    if ($is_new) {
      $User->set("email", Input::post("email"))
        ->set("total_deposit", 0)
        ->set("site_id", $AuthSite->get("id"))
        ->set("balance", 0);
    }

    if (mb_strlen(Input::post("password")) > 0) {
      $passhash = password_hash(Input::post("password"), PASSWORD_DEFAULT);
      $User->set("password", $passhash);
    }

    if ($AuthUser->get("id") != $User->get("id")) {
      // Don't allow to change self account type, status or expire date
      // This could cause to lost of access to the app with
      // default (and only) admin account
      $Role = Controller::model("Role", Input::post("account-type"));
      if (!$Role->isAvailable() || $Role->get("site_id") != $AuthSite->get("id")) {
        $this->resp->msg = "Vai trò không tồn tại!";
        $this->jsonecho();
      }
      // Account type
      if ($User->get("site_id") == $AuthSite->get("id")) {
        $User->set("account_type", $Role->get("idname"));
      }

      if ($User->get("role_id") != 2) {
        $User->set("role_id", $Role->get("id"));
      }
      $User->set("is_active", Input::post("status") == 1 ? 1 : 0);
    }

    $User->save();

    // update cookies
    if ($User->get("id") == $AuthUser->get("id")) {
      setcookie("nplh", $AuthUser->get("id") . "." . md5($User->get("password")), 0, "/");
    }


    $this->resp->result = 1;
    if ($is_new) {
      $this->resp->msg = "Đã thêm người dùng thành công! Vui lòng làm mới trang.";
      $this->resp->reset = true;
    } else {
      $this->resp->msg = "Đã lưu thay đổi";
    }
    $this->jsonecho();
  }
}
