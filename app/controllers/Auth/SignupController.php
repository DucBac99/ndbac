<?php
namespace Auth;
use Controller;
use Input;
use Event;
/**
 * Signup Controller
 */
class SignupController extends Controller
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

    $recaptcha_enabled = false;
    if (
      get_option("recaptcha_site_key") &&
      get_option("recaptcha_secret_key") &&
      get_option("signup_recaptcha_verification")
    ) {
      $recaptcha_enabled = true;
    }

    $Integrations = Controller::model("GeneralData", "integrations");

    $this->setVariable("recaptcha_enabled", $recaptcha_enabled)
      ->setVariable("Integrations", $Integrations);

    if (Input::post("action") == "signup") {
      $this->signup();
    }

    $this->view("auth/signup");
  }


  /**
   * Signup
   * @return void
   */
  private function signup()
  {
    $AuthSite = $this->getVariable("AuthSite");

    $errors = [];

    $required_fields  = [
      "firstname", "lastname", "email", "password"
    ];

    $required_ok = true;
    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $required_ok = false;
      }
    }

    if (!$required_ok) {
      $errors[] = "Vui lòng nhập đầy đủ thông tin";
    }


    if (empty($errors)) {
      if (!filter_var(Input::post("email"), FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ!";
      } else {
        $User = Controller::model("User", Input::post("email"));
        if ($User->isAvailable()) {
          $errors[] = "Email không khả dụng!";
        }
      }

      if (mb_strlen(Input::post("password")) < 6) {
        $errors[] = "Mật khẩu phải dài ít nhất 6 ký tự!";
      }
    }

    if (empty($errors)) {
      $User->set("email", strtolower(Input::post("email")))
        ->set(
          "password",
          password_hash(Input::post("password"), PASSWORD_DEFAULT)
        )
        ->set("firstname", Input::post("firstname"))
        ->set("lastname", Input::post("lastname"))
        ->set("total_deposit", 0)
        ->set("account_type", "member")
        ->set("role_id", 3)
        ->set("balance", 0)
        ->set("is_active", 1)
        ->set("site_id", $AuthSite->get("id"))
        ->save();

      // Check is email verification setting is ON
      if ($AuthSite->get("email_settings.email_verification")) {
        // Send verification email to this new user
        $User->sendVerificationEmail();
      }

      // Fire user.signup event
      Event::trigger("user.signup", $User);

      // Logging in
      setcookie("nplh", $User->get("id") . "." . md5($User->get("password")), 0, "/");


      header("Location: " . APPURL . "/home");
      exit;
    }

    $this->setVariable("FormErrors", $errors);

    return $this;
  }
}
