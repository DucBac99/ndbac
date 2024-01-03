<?php

namespace Services;

use Controller;
use DB;
use Input;

/**
 * Pricing Controller
 */
class PricingController extends Controller
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

    $Sites = Controller::model("Sites");
    $Sites->fetchData();

    $Servers = Controller::model("Servers");
    $Servers->fetchData();

    $this->setVariable("Sites", $Sites)
      ->setVariable("Service", $Service)
      ->setVariable("Servers", $Servers)
      ->setVariable("page", "pricing");

    if (Input::post("action") == "save") {
      $this->save();
    } else if (Input::post("action") == "list_price_by_domain") {
      $this->list_price_by_domain();
    }
    $this->view("services/detail");
  }

  public function save()
  {
    $this->resp->result = 0;
    $Service = $this->getVariable("Service");
    $AuthSite = $this->getVariable("AuthSite");

    if (!is_array(Input::post("data")) || !Input::post("server_id")) {
      $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
      $this->jsonecho();
    }

    $Server = Controller::model("Server", Input::post("server_id"));
    if (!$Server->isAvailable()) {
      $this->resp->msg = "Server không hợp lệ";
      $this->jsonecho();
    }

    $data = Input::post("data");
    foreach ($data as $site_id => $list) {
      $data_price = [];
      $data_amount = [];
      foreach ($list as $type => $roles) {
        if ($type == "price") {
          foreach ($roles as $role => $price) {
            $data_price[$role] = abs(intval($price));
          }
        } else if ($type == "amount") {
          foreach ($roles as $role => $amount) {
            $data_amount[$role]['min'] = abs(intval($amount['min']));
            $data_amount[$role]['max'] = abs(intval($amount['max']));
          }
        }
      }

      DB::table(TABLE_PREFIX . TABLE_SERVICE_SETTINGS)
        ->where("service_id", $Service->get("id"))
        ->where("site_id", $site_id)
        ->where("server_id", $Server->get("id"))
        ->onDuplicateKeyUpdate(array(
          "amount" => json_encode($data_amount),
          "price" => json_encode($data_price),
        ))
        ->insert(array(
          "service_id" => $Service->get("id"),
          "server_id" => $Server->get("id"),
          "site_id" => $site_id,
          "options" => json_encode(array(
            'maintain' => false,
            'public' => true,
          )),
          "amount" => json_encode($data_amount),
          "price" => json_encode($data_price),
        ));
    }

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();
  }

  public function list_price_by_domain()
  {
    $this->resp->result = 0;
    $Service = $this->getVariable("Service");
    $AuthSite = $this->getVariable("AuthSite");

    $site_id = Input::post("site_id");
    if (!$site_id) {
      $site_id = $AuthSite->get("id");
    }

    $server_id = Input::post("server_id");
    if (!$server_id) {
      $server_id = 1;
    }

    $price = [];
    $amount = [];
    $query = DB::table(TABLE_PREFIX . TABLE_ROLES)
      ->leftJoin(
        TABLE_PREFIX . TABLE_SITES,
        TABLE_PREFIX . TABLE_ROLES . ".site_id",
        "=",
        TABLE_PREFIX . TABLE_SITES . ".id"
      )
      ->select([
        TABLE_PREFIX . TABLE_ROLES . ".idname",
        TABLE_PREFIX . TABLE_ROLES . ".title",
        TABLE_PREFIX . TABLE_ROLES . ".site_id",
        TABLE_PREFIX . TABLE_ROLES . ".color",
        TABLE_PREFIX . TABLE_SITES . ".domain"

      ])
      ->where(TABLE_PREFIX . TABLE_ROLES . ".id", ">", 3);


    // if (!$site_id) {
    //   $settings = DB::table(TABLE_PREFIX . TABLE_SERVICE_SETTINGS)
    //     ->where(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".service_id", "=", $Service->get("id"))
    //     ->get();

    //   foreach ($settings as $setting) {
    //     $price["site_id_" . $setting->site_id] = json_decode($setting->price, true);
    //     $amount["site_id_" . $setting->site_id] = json_decode($setting->amount, true);
    //   }
    // } else 

    $query->where(TABLE_PREFIX . TABLE_ROLES . ".site_id", "=", $site_id);
    $settings = DB::table(TABLE_PREFIX . TABLE_SERVICE_SETTINGS)
      ->where(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".service_id", "=", $Service->get("id"))
      ->where(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".site_id", "=", $site_id)
      ->where(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".server_id", "=", $server_id)
      ->get();

    foreach ($settings as $setting) {
      $price["site_id_" . $site_id] = json_decode($setting->price, true);
      $amount["site_id_" . $site_id] = json_decode($setting->amount, true);
    }

    $roles = $query->get();


    $this->resp->result = 1;
    $this->resp->price = $price;
    $this->resp->amount = $amount;
    $this->resp->roles = $roles;
    $this->jsonecho();
  }
}
