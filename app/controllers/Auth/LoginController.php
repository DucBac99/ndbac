<?php

namespace Auth;

use Controller;
use Input;
use Event;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Login Controller
 */
class LoginController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");
    if ($AuthUser) {
      header("Location: " . APPURL . "/dashboard");
      exit;
    }

    if (Input::post("action") == "login") {
      $this->login();
    }

    if (Input::get("action") == "login_token") {
      $this->loginToken();
    }
    $this->view("auth/login");
  }

  /**
   * Login
   * @return void
   */
  private function login()
  {
    $AuthSite = $this->getVariable("AuthSite");
    $username = Input::post("username");
    $password = Input::post("password");
    $remember = Input::post("remember");

    $error = "Thông tin đăng nhập không hợp lệ!";
    if ($username && $password) {
      $User = Controller::model("User", $username);

      if (
        $User->isAvailable() &&
        $User->get("site_id") == $AuthSite->get("id") &&
        password_verify($password, $User->get("password"))
      ) {
        if (!$User->get("is_active")) {
          $error = "Tài khoản bị khoá. Hãy liên hệ để mở khoá lại!";
        } else {
          $exp = $remember ? time() + 86400 * 30 : 0;
          setcookie("nplh", $User->get("id") . "." . md5($User->get("password")), $exp, "/");

          if ($remember) {
            setcookie("nplrmm", "1", $exp, "/");
          } else {
            setcookie("nplrmm", "1", time() - 30 * 86400, "/");
          }

          // Fire user.signin event
          Event::trigger("user.signin", $User);

          header("Location: " . APPURL . "/dashboard");
          exit;
        }
      } else {
        $error = "Thông tin đăng nhập không hợp lệ!";
      }
    }
    $this->setVariable("FormError", $error);
    return $this;
  }

  private function loginToken()
  {
    $AuthSite = $this->getVariable("AuthSite");
    $accessToken = Input::get("accessToken");

    if (!$accessToken) {
      $this->setVariable("error_msg", "Thông tin đăng nhập không hợp lệ");
      $this->view("auth/error");
      exit;
    }

    try {
      $decoded = JWT::decode($accessToken, new Key(NP_SALT, 'HS256'));
    } catch (\Exception $ex) {
      $this->setVariable("error_msg", "Thông tin đăng nhập không hợp lệ");
      $this->view("auth/error");
      exit;
    }

    if (empty($decoded->id) || empty($decoded->password)) {
      $this->setVariable("error_msg", "Thông tin đăng nhập không hợp lệ");
      $this->view("auth/error");
      exit;
    }

    $User = Controller::Model("User", $decoded->id);
    if (
      $User->isAvailable() &&
      $User->get("site_id") == $AuthSite->get("id") &&
      md5($User->get("email")) == $decoded->password
    ) {
      if (!$User->get("is_active")) {
        $this->setVariable("error_msg", "Tài khoản bị khoá. Hãy liên hệ để mở khoá lại!");
        $this->view("auth/error");
        exit;
      } else {
        $exp =  0;
        setcookie("nplh", $User->get("id") . "." . md5($User->get("password")), $exp, "/");
        setcookie("nplrmm", "1", time() - 30 * 86400, "/");
        // Fire user.signin event
        Event::trigger("user.signin", $User);
        header("Location: " . APPURL . "/dashboard");
        exit;
      }
    } else {
      $this->setVariable("error_msg", "Xác thực không thành công.");
      $this->view("auth/error");
      exit;
    }
  }
}
