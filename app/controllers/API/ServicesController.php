<?php

namespace API;

use Controller;
use Input;
use DB;
use Exception;

/**
 * Services Controller
 */
class ServicesController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $this->services();
  }

  public function services()
  {
    $this->resp->result = 0;
    $AuthSite = $this->getVariable("AuthSite");
    $AuthUser = $this->getVariable("AuthUser");

    $group = Input::get("group");
    if (!in_array($group, ["facebook", "youtube", "tiktok"])) {
      $this->resp->error = 'GROUP_NOT_FOUND';
      $this->resp->msg = 'Group không hợp lệ';
      $this->jsonecho();
    }

    try {
      $services = DB::table(TABLE_PREFIX . TABLE_SERVICES)
        ->select([
          TABLE_PREFIX . TABLE_SERVICES . ".id",
          TABLE_PREFIX . TABLE_SERVICES . ".idname",
          TABLE_PREFIX . TABLE_SERVICES . ".title",
          TABLE_PREFIX . TABLE_SERVICES . ".warranty",
          TABLE_PREFIX . TABLE_SERVICES . ".is_maintaince",
        ])
        ->where(TABLE_PREFIX . TABLE_SERVICES . ".is_public", "=", 1)
        ->where(TABLE_PREFIX . TABLE_SERVICES . ".group", "=", $group)
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->data = array_map(function ($item) {
      $item->id = (int) $item->id;
      $item->warranty = (int) $item->warranty;
      $item->is_maintaince = (bool) $item->is_maintaince;
      return $item;
    }, $services);
    $this->resp->result = 1;
    $this->jsonecho();
  }
}
