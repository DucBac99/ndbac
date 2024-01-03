<?php

/**
 * PricingDetails Controller
 */
class PricingDetailsController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");
    // Auth
    if (!$AuthUser) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    if (Input::post("action") == "get") {
      $this->get();
    }
    $Servers = DB::table(TABLE_PREFIX . TABLE_SERVERS)
      ->where("is_public", "=", 1)
      ->get();
    $this->setVariable("Servers", $Servers);
    $this->view("pricing-details");
  }

  private function get()
  {
    $this->resp->result = 0;
    $Service = $this->getVariable("Service");
    $AuthSite = $this->getVariable("AuthSite");
    $server_id = Input::post("server_id");
    $group = Input::post("group");

    if (!$server_id) {
      $this->resp->msg = "Server id không tồn tại!";
      $this->jsonecho();
    }

    $Server = Controller::model("Server", $server_id);
    if (!$Server->isAvailable() || !$Server->get("is_public")) {
      $this->resp->msg = "Server không tồn tại!";
      $this->jsonecho();
    }


    $Roles = DB::table(TABLE_PREFIX . TABLE_ROLES)
      ->where("id", ">", 3)
      ->where("site_id", "=", $AuthSite->get("id"))
      ->select(["id", "idname", "title", "color", "amount"])
      ->get();


    $Services = DB::table(TABLE_PREFIX . TABLE_SERVICES)
      ->leftJoin(
        TABLE_PREFIX . TABLE_SERVICE_SETTINGS,
        function ($table) use ($AuthSite, $server_id) {
          $table->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".service_id", "=", TABLE_PREFIX . TABLE_SERVICES . ".id");
          $table->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".site_id", "=", DB::raw($AuthSite->get("id")));
          $table->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".server_id", "=", DB::raw($server_id));
        }
      )
      ->leftJoin(
        TABLE_PREFIX . TABLE_SERVICE_TITLES,
        function ($table) use ($AuthSite) {
          $table->on(TABLE_PREFIX . TABLE_SERVICE_TITLES . ".service_id", "=", TABLE_PREFIX . TABLE_SERVICES . ".id");
          $table->on(TABLE_PREFIX . TABLE_SERVICE_TITLES . ".site_id", "=", DB::raw($AuthSite->get("id")));
        }
      )
      ->where(TABLE_PREFIX . TABLE_SERVICES . ".group", "=", $group)
      ->select([
        TABLE_PREFIX . TABLE_SERVICES . ".id",
        TABLE_PREFIX . TABLE_SERVICES . ".idname",
        TABLE_PREFIX . TABLE_SERVICES . ".title",
        TABLE_PREFIX . TABLE_SERVICES . ".speed",
        TABLE_PREFIX . TABLE_SERVICES . ".icon",
        TABLE_PREFIX . TABLE_SERVICES . ".is_public",
        TABLE_PREFIX . TABLE_SERVICES . ".is_maintaince"
      ])
      ->select([
        TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".price",
        TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".options",
      ])
      ->select([
        DB::raw(TABLE_PREFIX . TABLE_SERVICE_TITLES . ".title as title_extra")
      ])
      ->get();


    $this->resp->result = 1;
    $this->resp->roles = $Roles;
    $this->resp->base_url = APPURL . "/orders/" . $group;
    $this->resp->services = array_map(function ($item) {
      $item->options = json_decode($item->options);
      $item->price = json_decode($item->price);
      return $item;
    }, $Services);
    $this->jsonecho();
  }
}
