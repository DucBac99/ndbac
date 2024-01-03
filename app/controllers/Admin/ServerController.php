<?php

namespace Admin;

use Input;
use Controller;
use DB;

/**
 * Server Controller
 */
class ServerController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $Route = $this->getVariable("Route");
    $AuthUser = $this->getVariable("AuthUser");

    // Auth
    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }


    $Server = Controller::model("Server");
    if (isset($Route->params->id)) {
      $Server->select($Route->params->id);

      if (!$Server->isAvailable()) {
        header("Location: " . APPURL . "/servers");
        exit;
      }
    }
    $this->setVariable("Server", $Server);

    if (Input::post("action") == "save") {
      $this->save();
    }

    $this->view("admin/server");
  }


  /**
   * Save (new|edit) Server
   * @return void 
   */
  private function save()
  {
    $this->resp->result = 0;
    $Server = $this->getVariable("Server");

    // Check if this is new or not
    $is_new = !$Server->isAvailable();

    // Check required fields
    $required_fields = ["name", "api_url", "api_key", "api_user_id"];

    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->jsonecho();
      }
    }

    // Start setting data
    $Server->set("name", Input::post("name"))
      ->set("api_url", Input::post("api_url"))
      ->set("api_key", Input::post("api_key"))
      ->set("api_user_id", Input::post("api_user_id"))
      ->set("is_public", Input::post("public") ? 1 : 0)
      ->set("is_maintaince", Input::post("maintaince") ? 1 : 0)
      ->set("allow_refund", Input::post("allow_refund") ? 1 : 0)
      ->save();


    $this->resp->result = 1;
    if ($is_new) {
      $this->resp->msg = "Đã thêm server thành công! Vui lòng làm mới trang.";
      $this->resp->reset = true;
    } else {
      $this->resp->msg = "Đã lưu thay đổi";
    }
    $this->jsonecho();
  }
}
