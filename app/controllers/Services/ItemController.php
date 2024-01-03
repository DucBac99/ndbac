<?php

namespace Services;

use Controller;
use DB;
use Input;

/**
 * Item Controller
 */
class ItemController extends Controller
{
  public function process()
  {
    $Route = $this->getVariable("Route");
    $AuthUser = $this->getVariable("AuthUser");

    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $Service = Controller::model("Service");
    if (isset($Route->params->id)) {
      $Service->select($Route->params->id);

      if (!$Service->isAvailable()) {
        header("Location: " . APPURL . "/services");
        exit;
      }
    }

    if ($Service->isAvailable()) {
      $Sites = Controller::model("Sites");
      $Sites->where("id", ">", 0)
        ->fetchData();
      $this->setVariable("Sites", $Sites);
    }

    $this->setVariable("Service", $Service);

    if (Input::post("action") == "save") {
      $this->save();
    }
    $this->view("services/detail");
  }

  public function save()
  {
    $this->resp->result = 0;
    $Service = $this->getVariable("Service");
    $AuthSite = $this->getVariable("AuthSite");

    $is_new = !$Service->isAvailable();

    $required_fields = ["title", "group", "icon", "idname", "speed"];

    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->jsonecho();
      }
    }

    $Service->set("title", Input::post("title"))
      ->set("group", Input::post("group"))
      ->set("icon", Input::post("icon"))
      ->set("warranty", (int)Input::post("warranty"))
      ->set("idname", Input::post("idname"))
      ->set("speed", Input::post("speed"))
      ->set("max_hold", Input::post("max_hold"))
      ->set("is_public", Input::post("public") ? 1 : 0)
      ->set("is_maintaince", Input::post("maintaince") ? 1 : 0)
      ->save();

    if (Input::post("title_extra")) {
      DB::table(TABLE_PREFIX . TABLE_SERVICE_TITLES)
        ->where("service_id", $Service->get("id"))
        ->where("site_id", $AuthSite->get("id"))
        ->onDuplicateKeyUpdate(array(
          "title" => Input::post("title_extra"),
        ))
        ->insert(array(
          "service_id" => $Service->get("id"),
          "site_id" => $AuthSite->get("id"),
          "title" => Input::post("title_extra"),
          "icon" => Input::post("icon")
        ));
    }

    $this->resp->result = 1;
    if ($is_new) {
      $this->resp->msg = "Đã thêm dịch vụ dùng thành công! Vui lòng làm mới trang.";
      $this->resp->reset = true;
    } else {
      $this->resp->msg = "Đã lưu thay đổi";
    }
    $this->jsonecho();
  }
}
