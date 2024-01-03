<?php

namespace API;

use Controller;
use Input;
use DB;
use Exception;

/**
 * Servers Controller
 */
class ServersController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $this->servers();
  }

  public function servers()
  {
    $this->resp->result = 0;
    $AuthSite = $this->getVariable("AuthSite");
    $AuthUser = $this->getVariable("AuthUser");

    $service_id = Input::get("service_id");
    if (!$service_id) {
      $this->resp->error = 'SERVICE_NOT_FOUND';
      $this->resp->msg = 'Service không hợp lệ';
      $this->jsonecho();
    }

    try {
      $ServersDB = DB::table(TABLE_PREFIX . TABLE_SERVICE_SETTINGS)
        ->leftJoin(
          TABLE_PREFIX . TABLE_SERVERS,
          TABLE_PREFIX . TABLE_SERVERS . ".id",
          "=",
          TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".server_id"
        )
        ->where(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".service_id", "=", $service_id)
        ->where(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".site_id", "=", $AuthSite->get("id"))
        ->select([
          TABLE_PREFIX . TABLE_SERVERS . ".name",
          TABLE_PREFIX . TABLE_SERVERS . ".id",
        ])
        ->select([
          TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".price",
          TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".amount",
          TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".options",
        ])
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }
    

    $this->resp->result = 1;
    $this->resp->data = array_map(function ($sv) use($AuthUser) {
      $sv->id = (int)$sv->id;

      $sv->price = json_decode($sv->price);
      $sv->price = get_real_price($sv->price, $sv->id, $AuthUser->get("idname"));

      $sv->amount = json_decode($sv->amount);
      $sv->amount = get_real_amount($sv->amount, $sv->id, $AuthUser->get("idname"));

      $sv->options = json_decode($sv->options);
      $sv->maintain = empty($sv->options->maintain) ? false : $sv->options->maintain;
      $sv->public = empty($sv->options->public) ? false : $sv->options->public;

      unset($sv->options);
      return $sv;
    }, $ServersDB);
    $this->jsonecho();
  }
}
