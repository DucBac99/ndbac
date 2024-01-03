<?php

namespace Profile;

use Controller;
use Input;
use DB;
use Cloudflare;
use Exception;
use bt_api;

class SiteAgencyController extends Controller
{
  public function process()
  {
    header("HTTP/1.0 404 Not Found");
    exit;

    $AuthUser = $this->getVariable("AuthUser");
    $Route = $this->getVariable("Route");

    if (!$AuthUser) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $Themes = Controller::model("Themes");
    $Themes->fetchData();

    $this->setVariable("Themes", $Themes);

    if (Input::post("action") == "save") {
      $this->save();
    }
    $this->view("profile/site-agency");
  }

  /**
   * Save new domain
   * @return json list 
   */
  private function save()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    if ($AuthUser->get("has_site")) {
      $this->resp->msg = "Bạn đã tạo site, không thể tạo lần nữa. Liên hệ admin nếu muốn thay đổi";
      $this->jsonecho();
    }
    // Check required fields
    $required_fields = ["domain", "name", "slogan", "description", "keywords", "theme"];
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

    $domain = Input::post("domain");
    if (!isValidDomain($domain)) {
      $this->resp->msg = "Domain không hợp lệ";
      $this->jsonecho();
    }

    $zone_id = "";
    $resp = Cloudflare::addDomain($domain);
    if (!empty($resp->success)) {
      $zone_id = $resp->result->id;
    } else if (!empty($resp->errors)) {
      $this->resp->msg = $resp->errors[0]->message;
      $this->jsonecho();
    } else {
      $this->resp->msg = "Thêm domain thất bại. Hãy thử lại #1";
      $this->jsonecho();
    }

    $list_dns_records = [
      [
        "type" => "A",
        "name" => $domain,
        "content" => DEFAULT_IP,
        "ttl" => 1,
        "proxied" => true
      ]
    ];

    foreach ($list_dns_records as $record) {
      Cloudflare::addDNSRecord($zone_id, $record);
    }

    $bt_id = 0;
    $api = new bt_api();
    $r_data = $api->AddSite($domain, ROOTPATH);
    if (empty($r_data->siteStatus) || $r_data->siteStatus != 1) {
      $this->resp->msg = !empty($r_data->msg) ? $r_data->msg : 'Lỗi thêm site #1';
      $this->jsonecho();
    }
    $bt_id = $r_data->siteId;

    $r_data = $api->SaveFileBody($domain, file_get_contents(APPPATH . '/sample/nginx.config'));
    if (empty($r_data->status) || $r_data->status != 1) {
      $this->resp->msg = !empty($r_data->msg) ? $r_data->msg : 'Lỗi thêm site #2';
      $this->jsonecho();
    }

    try {
      $settings = [];
      $settings["site_name"] = Input::post("name");
      $settings["site_slogan"] = Input::post("slogan");
      $settings["site_description"] = Input::post("description");
      $settings["site_keywords"] = Input::post("keywords");

      $options = [];
      $options["maintenance_mode"] = false;
      // Start setting data

      $site_id = DB::table(TABLE_PREFIX . TABLE_SITES)->insert(array(
        "domain" => $domain,
        "is_active" => 1,
        "is_root" => 0,
        "theme" => $Theme->get("idname"),
        "bt_id" => $bt_id,
        "zone_id" => $zone_id,
        "options" => json_encode($options),
        "settings" => json_encode($settings),
        "email_settings" => "{}",
        "banking" => "{}",
      ));

      $AuthUser->set("has_site", 1)->save();
    } catch (Exception $ex) {
      $this->resp->msg = "Thêm domain thất bại. Hãy thử lại #2";
      $this->resp->error = $ex->getMessage();
      $this->jsonecho();
    };

    $this->resp->result = 1;
    $this->resp->msg = "Site của bạn đã được xác nhận. Hãy chờ một tí để admin setup";
    $this->jsonecho();
  }
}
