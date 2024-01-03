<?php

namespace Sites;

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

    // Auth
    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $Site = Controller::model("Site");
    if (isset($Route->params->id)) {
      $Site->select($Route->params->id);
      if (!$Site->isAvailable() || $Site->get("id") == 1) {
        header("Location: " . APPURL . "/sites");
        exit;
      }
    }

    $Themes = Controller::model("Themes");
    $Themes->fetchData();

    $this->setVariable("Site", $Site)
      ->setVariable("Themes", $Themes);

    if (Input::post("action") == "save") {
      $this->save();
    }

    $this->view("sites/edit");
  }


  /**
   * Save (new|edit) site
   * @return void 
   */
  private function save()
  {
    $this->resp->result = 0;
    $Site = $this->getVariable("Site");

    // Check if this is new or not
    $is_new = !$Site->isAvailable();

    // Check required fields
    $required_fields = ["name", "slogan", "description", "keywords", "theme"];
    if ($is_new) {
      $required_fields[] = "domain";
    }
    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->jsonecho();
      }
    }

    $Theme = Controller::model("Theme", Input::post("theme"));
    if (!$Theme->isAvailable()) {
      $this->resp->msg = "Giao diện không tồn tại";
      $this->jsonecho();
    }

    if ($is_new) {
      $Site->set("domain", Input::post("domain"));
    }

    $settings = [];
    $settings["site_name"] = Input::post("name");
    $settings["site_slogan"] = Input::post("slogan");
    $settings["site_description"] = Input::post("description");
    $settings["site_keywords"] = Input::post("keywords");


    $options = [];
    $options["maintenance_mode"] = Input::post("maintenance-mode") ? true : false;
    // Start setting data
    $Site->set("settings", json_encode($settings))
      ->set("options", json_encode($options))
      ->set("theme", $Theme->get("idname"))
      ->set("is_active", INput::post("status") ? 1 : 0)
      ->save();


    $this->resp->result = 1;
    if ($is_new) {
      $this->resp->msg = "Đã thêm site thành công! Vui lòng làm mới trang.";
      $this->resp->reset = true;
    } else {
      $this->resp->msg = "Đã lưu thay đổi";
    }
    $this->jsonecho();
  }
}
