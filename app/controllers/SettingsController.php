<?php

/**
 * Settings Controller
 */
class SettingsController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");
    $Route = $this->getVariable("Route");

    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $page = isset($Route->params->page) ? $Route->params->page : "site";

    $this->setVariable("page", $page);

    if (Input::post("action") == "save") {
      $this->save();
    }
    $this->view("settings");
  }


  /**
   * Save changes
   * @return boolean 
   */
  private function save()
  {
    $page = $this->getVariable("page");

    $method = "save";
    $parts = explode("-", $page);
    foreach ($parts as $p) {
      $method .= ucfirst(strtolower($p));
    }

    return $this->$method();
  }


  /**
   * Save site settings
   * @return boolean 
   */
  private function saveSite()
  {
    $AuthSite = $this->getVariable("AuthSite");
    $do_save = false;


    if (!is_null(Input::post("name"))) {
      $AuthSite->set("settings.site_name", Input::post("name"));
      $do_save = true;
    }

    if (!is_null(Input::post("description"))) {
      $AuthSite->set("settings.site_description", Input::post("description"));
      $do_save = true;
    }

    if (!is_null(Input::post("slogan"))) {
      $AuthSite->set("settings.site_slogan", Input::post("slogan"));
      $do_save = true;
    }

    if (!is_null(Input::post("keywords"))) {
      $AuthSite->set("settings.site_keywords", Input::post("keywords"));
      $do_save = true;
    }

    if ($do_save) {
      $AuthSite->save();
    }

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }


  /**
   * Save logotype
   * @return boolean 
   */
  private function saveLogotype()
  {
    $AuthSite = $this->getVariable("AuthSite");
    $do_save = false;

    if (!is_null(Input::post("logotype"))) {
      $AuthSite->set("settings.logotype", Input::post("logotype"));
      $do_save = true;
    }

    if (!is_null(Input::post("logomark"))) {
      $AuthSite->set("settings.logomark", Input::post("logomark"));
      $do_save = true;
    }

    if ($do_save) {
      $AuthSite->save();
    }

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }


  /**
   * Save other settings
   * @return boolean 
   */
  private function saveOther()
  {
    $AuthSite = $this->getVariable("AuthSite");

    $AuthSite->set("options.maintenance_mode", Input::post("maintenance-mode") ? true : false)
      ->set("options.support_url", Input::post("support-url"))
      ->set("options.instruction_url", Input::post("instruction-url"))
      ->set("options.licenseKey", Input::post("license-key"))
      ->save();

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }

  /**
   * Save order settings
   * @return boolean 
   */
  private function saveOrder()
  {
    if (Input::post("max-hold")) {
      save_option("MAX_HOLD", (int)Input::post("max-hold"));
    }

    if (Input::post("max-order-running")) {
      save_option("MAX_ORDER_RUNNING", (int)Input::post("max-order-running"));
    }

    if (Input::post("max-pause")) {
      save_option("MAX_PAUSE", (int)Input::post("max-pause"));
    }

    if (Input::post("max-status-pause")) {
      save_option("MAX_STATUS_PAUSE", (int)Input::post("max-status-pause"));
    }

    if (Input::post("max-tt")) {
      save_option("MAX_TT", (int)Input::post("max-tt"));
    }

    if (Input::post("max-order-hold")) {
      save_option("MAX_ORDER_HOLD", (int)Input::post("max-order-hold"));
    }

    if (Input::post("type_proxy_for_order")) {
      save_option("type_proxy_for_order", Input::post("type_proxy_for_order"));
    }

    if (Input::post("max-num-amount-group-order")) {
      save_option("MAX_NUM_AMOUNT_GROUP_ORDER", Input::post("max-num-amount-group-order"));
    }

    save_option("CLOSE_ORDER_ALL_SERVICES", (bool)Input::post("close-order-all-service"));

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }

  /**
   * Save SMTP settings
   * @return boolean 
   */
  private function saveSmtp()
  {
    $AuthSite = $this->getVariable("AuthSite");

    if (Input::post("host")) {
      $host = Input::post("host");
      $port = Input::post("port");
      $encryption = strtolower(Input::post("encryption"));
      if (!in_array($encryption, ["ssl", "tls"])) {
        $encryption = "";
      }
      $auth = (bool)Input::post("auth");
      $username = $auth ? Input::post("username") : "";
      $password = $auth ? Input::post("password") : "";
      $from = Input::post("from");

      if (!in_array($port, [25, 465, 587])) {
        $this->resp->msg = __("Invalid port number");
        $this->jsonecho();
      }

      if ($from && !filter_var($from, FILTER_VALIDATE_EMAIL)) {
        $this->resp->msg = __("From email is not valid");
        $this->jsonecho();
      }

      // Check SMTP Connection
      $smtp = new SMTP;
      $connected = false;
      // $smtp->do_debug = SMTP::DEBUG_CONNECTION;

      try {
        //Connect to an SMTP server
        $options = [];

        // If your mail server is on GoDaddy
        // Probably you should uncomment following 5 lines
        // 
        // $options["ssl"] = [
        //     'verify_peer' => false,
        //     'verify_peer_name' => false,
        //     'allow_self_signed' => true
        // ];

        if (!$smtp->connect($host, $port, 30, $options)) {
          $this->resp->msg = __("Connection failed");
          $this->jsonecho();
        }

        //Say hello
        if (!$smtp->hello(gethostname())) {
          $this->resp->msg = __("Connection failed");
          $this->jsonecho();
        }

        //Get the list of ESMTP services the server offers
        $e = $smtp->getServerExtList();

        //If server can do TLS encryption, use it
        if (is_array($e) && array_key_exists('STARTTLS', $e)) {
          $tlsok = $smtp->startTLS();

          if (!$tlsok) {
            $this->resp->msg = __("Failed to start encryption");
            $this->jsonecho();
          }

          //Repeat EHLO after STARTTLS
          if (!$smtp->hello(gethostname())) {
            $this->resp->msg = __("Encryption failed");
            $this->jsonecho();
          }

          //Get new capabilities list, which will usually now include AUTH if it didn't before
          $e = $smtp->getServerExtList();
        }

        //If server supports authentication, do it (even if no encryption)
        if ($auth && is_array($e) && array_key_exists('AUTH', $e)) {
          if ($smtp->authenticate($username, $password)) {
            $connected = true;
          } else {
            $this->resp->msg = __("Authentication failed");
            $this->jsonecho();
          }
        }
      } catch (Exception $e) {
        $this->resp->msg = __("Connection failed");
        $this->jsonecho();
      }

      $smtp->quit(true);

      if (!$connected) {
        $this->resp->msg = __("Authentication failed");
        $this->jsonecho();
      }


      // Encrypt the password
      try {
        $passhash = Defuse\Crypto\Crypto::encrypt(
          $password,
          Defuse\Crypto\Key::loadFromAsciiSafeString(CRYPTO_KEY)
        );
      } catch (\Exception $e) {
        $this->resp->msg = $e->getMessage();
        $this->jsonecho();
      }


      $settings = [
        "host" => $host,
        "port" => $port,
        "encryption" => $encryption,
        "auth" => $auth,
        "username" => $username,
        "password" => $passhash,
        "from" => $from
      ];
    } else {
      $settings = [
        "host" => "",
        "port" => "",
        "encryption" => "",
        "auth" => false,
        "username" => "",
        "password" => "",
        "from" => ""
      ];
    }

    $data = json_decode($AuthSite->get("email_settings"), true);
    $data["smtp"] = $settings;
    $AuthSite->set("email_settings", json_encode($data));
    $AuthSite->save();

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }

  /**
   * Save recaptcha settings
   * @return [self] 
   */
  private function saveRecaptcha()
  {
    $AuthSite = $this->getVariable("AuthSite");

    $AuthSite->set("options.recaptcha_site_key", Input::post("site-key"))
      ->set("options.recaptcha_secret_key", Input::post("secret-key"))
      ->set("options.signup_recaptcha_verification", Input::post("signup-recaptcha-verification") ? true : false)
      ->set("options.signin_recaptcha_verification", Input::post("signin-recaptcha-verification") ? true : false)
      ->save();

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }

  /**
   * Save Topup settings
   * @return boolean 
   */
  private function saveTopup()
  {
    $AuthSite = $this->getVariable("AuthSite");

    $auth = array();
    $auth["JWT"] = "JWT123123";
    $auth["cookie"] = "ssid=123456";
    $auth["username"] = Input::post("username");
    $passhash = Input::post("password");
    try {
      $passhash = \Defuse\Crypto\Crypto::encrypt(
        Input::post("password"),
        \Defuse\Crypto\Key::loadFromAsciiSafeString(CRYPTO_KEY)
      );
    } catch (\Exception $e) {
      $this->resp->msg = "Mã hoá thất bại";
      $this->jsonecho();
    }
    $auth["password"] = $passhash;
    $auth["bank_code"] = Input::post("bank_code");
    $auth["template"] = Input::post("template");
    $auth["status"] = true;


    $info =  array();
    $info["account_number"] = Input::post("account_number");
    $info["account_name"] = Input::post("account_name");
    $info["branch"] = Input::post("branch");
    $info["bank"] = Input::post("bank");
    $info["content"] = Input::post("content");


    $data = json_decode($AuthSite->get("banking"), true);
    $data["auth"] = $auth;
    $data["info"] = $info;

    $AuthSite->set("banking", json_encode($data))->save();

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }
}
