<?php

namespace Admin;

use Input;
use Controller;
use DB;

/**
 * Effects Controller
 */
class EffectsController extends Controller
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

    $this->setVariable("page", "effects");
    if (Input::post("action") == "save") {
      $this->save();
    }

    $this->view("admin/effects");
  }


  /**
   * Save (new|edit) effect
   * @return void 
   */
  private function save()
  {
    $this->resp->result = 0;
    $AuthSite = $this->getVariable("AuthSite");

    // Check required fields
    $required_fields = ["effect_name"];

    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->jsonecho();
      }
    }

    // Start setting data
    $AuthSite->set("options.effect", Input::post("effect_name"))
      ->save();


    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();
  }
}
