<?php

namespace Services;

use Controller;
use DB;
use Input;

/**
 * Servers Controller
 */
class ServersController extends Controller
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
    }


    if (!$Service->isAvailable()) {
      header("Location: " . APPURL . "/services");
      exit;
    }
    $this->setVariable("Service", $Service);
    if (Input::post("action") == "save") {
      $this->save();
    } else if (Input::post("action") == "note") {
      $this->note();
    } else if (Input::post("action") == "list_server_by_domain") {
      $this->list_server_by_domain();
    }
  }

  public function save()
  {
    $this->resp->result = 0;
    $Service = $this->getVariable("Service");
    $AuthSite = $this->getVariable("AuthSite");
    $site_id = Input::post("site_id");

    if (!$site_id) {
      $site_id = $AuthSite->get("id");
    }

    if (is_array(Input::post("data"))) {
      foreach (Input::post("data") as $sv_id => $option) {
        $options = array(
          'maintain' => isset($option["maintain"]) && $option["maintain"] == 1 ? true : false,
          'public' => isset($option["public"]) && $option["public"] == 1 ? true : false,
        );
        DB::table(TABLE_PREFIX . TABLE_SERVICE_SETTINGS)
          ->where("service_id", $Service->get("id"))
          ->where("site_id", $site_id)
          ->where("server_id", $sv_id)
          ->onDuplicateKeyUpdate(array(
            "options" => json_encode($options),
          ))
          ->insert(array(
            "service_id" => $Service->get("id"),
            "server_id" => $sv_id,
            "site_id" => $site_id,
            "options" => json_encode($options),
            "amount" => '{}',
            "price" => '{}',
          ));
      }
    }




    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();
  }

  public function note()
  {
    $this->resp->result = 0;
    $Service = $this->getVariable("Service");

    if (Input::post("note")) {
      $Service->set("note", Input::post("note"))
        ->save();
    }

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();
  }

  private function list_server_by_domain()
  {
    $this->resp->result = 0;
    $Service = $this->getVariable("Service");
    $AuthSite = $this->getVariable("AuthSite");
    $site_id = Input::post("site_id");

    if (!$site_id) {
      $site_id = $AuthSite->get("id");
    }

    $Servers = DB::table(TABLE_PREFIX . TABLE_SERVERS)
      ->leftJoin(
        TABLE_PREFIX . TABLE_SERVICE_SETTINGS,
        function ($qb) use ($Service, $site_id) {
          $qb->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".server_id", "=", TABLE_PREFIX . TABLE_SERVERS . ".id");
          $qb->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".service_id", "=", DB::raw($Service->get("id")));
          $qb->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".site_id", "=", DB::raw($site_id));
        }
      )
      ->where(TABLE_PREFIX . TABLE_SERVERS . ".is_public", "=", 1)
      ->select(TABLE_PREFIX . TABLE_SERVERS . ".*")
      ->select([
        TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".options"
      ])
      ->get();

    $this->resp->result = 1;
    $this->resp->data = array_map(function ($item) {
      $item->options = json_decode($item->options);
      return $item;
    }, $Servers);
    $this->jsonecho();
  }
}
