<?php

namespace Admin;

use Input;
use Controller;
use DB;

/**
 * Theme Controller
 */
class ThemeController extends Controller
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


    $Theme = Controller::model("Theme");
    if (isset($Route->params->id)) {
      $Theme->select($Route->params->id);

      if (!$Theme->isAvailable()) {
        header("Location: " . APPURL . "/themes");
        exit;
      }
    }
    $this->setVariable("Theme", $Theme)
      ->setVariable("page", "themes");

    if (Input::post("action") == "save") {
      $this->save();
    }

    $this->view("admin/theme");
  }


  /**
   * Save (new|edit) Theme
   * @return void 
   */
  private function save()
  {
    $this->resp->result = 0;
    $Theme = $this->getVariable("Theme");

    // Check if this is new or not
    $is_new = !$Theme->isAvailable();

    // Check required fields
    $required_fields = ["idname", "thumb"];

    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->jsonecho();
      }
    }

    // Start setting data
    $Theme->set("idname", Input::post("idname"))
      ->set("thumb", Input::post("thumb"))
      ->save();


    $this->resp->result = 1;
    if ($is_new) {
      $this->resp->msg = "Đã thêm giao diện thành công! Vui lòng làm mới trang.";
      $this->resp->reset = true;
    } else {
      $this->resp->msg = "Đã lưu thay đổi";
    }
    $this->jsonecho();
  }
}
