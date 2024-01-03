<?php

namespace API;

use Controller;
use Input;
//https://documenter.getpostman.com/view/4725791/2s9Xy5MWB1

/**
 * Docs Controller
 */
class DocsController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");
    // Auth
    if (!$AuthUser) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    if (Input::post("action") == "renew") {
      $this->renew();
    }

    header("HTTP/1.0 404 Not Found");
    exit;
  }

  private function renew()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    $api_key = bin2hex(random_bytes(16));
    try {
      $AuthUser->set("api_key", $api_key)->save();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->api_key = $AuthUser->get("id") . "." . $api_key;
    $this->resp->msg = "Làm mới api key thành công";
    $this->jsonecho();
  }
}
