<?php

namespace Sites;

use Controller;
use DB;
use Input;
use Exception;

/**
 * List Controller
 */
class ListController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");

    // Auth
    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }
    if (Input::get("draw")) {
      $this->getSites();
    } else if (Input::post("action") == "remove") {
      $this->remove();
    } else if (Input::post("action") == "set_admin") {
      $this->set_admin();
    } else if (Input::post("action") == "change_domain") {
      $this->change_domain();
    }

    $this->view("sites/list");
  }


  /**
   * Remove Site
   * @return void 
   */
  private function remove()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    if (!Input::post("id")) {
      $this->resp->msg = "Cần nhập ID!";
      $this->jsonecho();
    }

    $Site = Controller::model("Site", Input::post("id"));

    if (!$Site->isAvailable() || $Site->get("id") == 1) {
      $this->resp->msg = "Site không tồn tại!";
      $this->jsonecho();
    }

    $checkUser = DB::table(TABLE_PREFIX . TABLE_USERS)
      ->where("site_id", "=", $Site->get("id"))
      ->select([
        DB::raw("COUNT(*) as total")
      ])
      ->get();

    if ($checkUser[0]->total > 0) {
      $this->resp->msg = "Site đã có người dùng, không thể xoá!";
      $this->jsonecho();
    }

    $api = new bt_api();
    $data = $api->deleteSite($Site->get("domain"), $Site->get("bt_id"));
    // if (!empty($data->status) || $data->status != 1) {
    //   $this->resp->msg = !empty($data->msg) ? $data->msg : 'Lỗi xoá site #1';
    //   $this->jsonecho();
    // }
    Cloudflare::removeDomain($Site->get("zone_id"));
    $Site->delete();

    $this->resp->result = 1;
    $this->resp->msg = "Xoá thành công";
    $this->jsonecho();
  }

  /**
   * Set Admin Site
   * @return void 
   */
  private function set_admin()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    if (!Input::post("id")) {
      $this->resp->msg = "Cần nhập ID site!";
      $this->jsonecho();
    }

    if (!Input::post("user_id")) {
      $this->resp->msg = "Cần nhập user Id!";
      $this->jsonecho();
    }

    if ($AuthUser->get("id") == Input::post("user_id")) {
      $this->resp->msg = "Không thể tự set admin cho chính mình!";
      $this->jsonecho();
    }

    $Site = Controller::model("Site", Input::post("id"));
    if (!$Site->isAvailable() || $Site->get("id") == 1) {
      $this->resp->msg = "Site không tồn tại!";
      $this->jsonecho();
    }

    $User = Controller::model("User", Input::post("user_id"));
    if (!$User->isAvailable() || !$User->get("is_active")) {
      $this->resp->msg = "User không tồn tại hoặc đã bị khoá!";
      $this->jsonecho();
    }

    if ($User->get("site_id") == $Site->get("id")) {
      $this->resp->msg = "User đang là admin site đang thực hiện!";
      $this->jsonecho();
    } else if ($User->get("site_id") > 1) {
      $this->resp->msg = "User đang là admin site khác!";
      $this->jsonecho();
    }

    $Site->set("user_id", $User->get("id"))
      ->save();

    $User->set("site_id", $Site->get("id"))
      ->set("role_id", 2)
      ->save();

    $api = new bt_api();
    $url_vcb = "https://" . $Site->get("domain") . "/cron/topup";
    $r_data = $api->AddCrontab($url_vcb, $url_vcb);
    if (empty($r_data->id)) {
      $this->resp->msg = 'Lỗi thêm cron hãy thử lại #1';
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->msg = "Set admin thành công";
    $this->jsonecho();
  }

  /**
   * Change domain Site
   * @return void 
   */
  private function change_domain()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    if (!Input::post("id")) {
      $this->resp->msg = "Cần nhập ID site!";
      $this->jsonecho();
    }

    $domain = Input::post("domain");
    if (!$domain || !isValidDomain($domain)) {
      $this->resp->msg = "Domain không hợp lệ";
      $this->jsonecho();
    }

    $Site = Controller::model("Site", Input::post("id"));
    if (!$Site->isAvailable() || $Site->get("id") == 1) {
      $this->resp->msg = "Site không tồn tại!";
      $this->jsonecho();
    }

    if ($Site->get("domain") == $domain) {
      $this->resp->msg = "Bạn đang nhập domain cũ!";
      $this->jsonecho();
    }

    // thêm mới domain
    $zone_id = "";
    $resp = Cloudflare::addDomain($domain);
    if (!empty($resp->success)) {
      $zone_id = $resp->result->id;
    } else {
      $this->resp->msg = "Thêm domain thất bại. Hãy thử lại #1";
      $this->jsonecho();
    }

    $api = new bt_api();
    $r_data = $api->DeleteSite($Site->get("domain"), $Site->get("bt_id"));
    if (empty($r_data->status) || $r_data->status != 1) {
      $this->resp->msg = !empty($r_data->msg) ? $r_data->msg : 'Lỗi xoá tên miền cũ #1';
      $this->jsonecho();
    }

    $r_data = $api->AddSite($domain, ROOTPATH);
    if (empty($r_data->siteStatus) || $r_data->siteStatus != 1) {
      $this->resp->msg = !empty($r_data->msg) ? $r_data->msg : 'Lỗi thêm site #4';
      $this->jsonecho();
    }
    $bt_id = $r_data->siteId;

    // xoá domain cũ
    Cloudflare::removeDomain($Site->get("zone_id"));

    Cloudflare::addDNSRecord($zone_id, [
      "type" => "A",
      "name" => $domain,
      "content" => DEFAULT_IP,
      "ttl" => 1,
      "proxied" => true
    ]);

    $Site
      ->set("domain", $domain)
      ->set("zone_id", $zone_id)
      ->set("bt_id", $bt_id)
      ->save();

    $this->resp->result = 1;
    $this->resp->msg = "Đổi tên miền thành công";
    $this->jsonecho();
  }

  /** 
   * Get Sites
   * @return void
   */
  private function getSites()
  {
    $this->resp->result = 0;

    $order = Input::get("order");
    $search = Input::get("search");
    $start = (int)Input::get("start");
    $draw = (int)Input::get("draw");
    $length = (int)Input::get("length");

    if ($draw) {
      $this->resp->draw = $draw;
    }

    $data = [];

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_SITES)
        ->where(TABLE_PREFIX . TABLE_SITES . ".id", ">", 1);

      $search_query = trim((string)$search);
      if ($search_query) {
        $query->where(function ($q) use ($search_query) {
          $q->where(TABLE_PREFIX . TABLE_SITES . ".domain", 'LIKE', $search_query . '%');
        });
      }

      if ($order && isset($order["column"]) && isset($order["dir"])) {
        $sort =  in_array($order["dir"], ["asc", "desc"]) ? $order["dir"] : "desc";
        $column_name = trim($order["column"]) != "" ? trim($order["column"]) : "id";
        if (in_array($column_name, ["id"])) {
          $query->orderBy(DB::raw("CAST(`" . TABLE_PREFIX . TABLE_SITES . "`.`" . $column_name . "` AS unsigned)"), $sort);
        } else if (strpos($column_name, ":") !== false) {
          $column_name = str_replace(":", "`.`", $column_name);
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . $column_name . "` "), $sort);
        } else if (strpos($column_name, ".") !== false) {
          $column_name = explode(".", $column_name);
          $table = array_shift($column_name);
          $path_json = [];
          foreach ($column_name as $f) {
            $path_json[] = $f;
          }
          $query->orderBy(DB::raw("`" . TABLE_PREFIX . TABLE_SITES . "`.`" . $table . '`->"$.' . join(".", $path_json) . '" '), $sort);
        } else {
          $query->orderBy(DB::raw("`" . $column_name . "` "), $sort);
        }
      } else {
        $query->orderBy(TABLE_PREFIX . TABLE_SITES . ".id", "DESC");
      }

      $subQuery = DB::table(TABLE_PREFIX . TABLE_USERS)->select([
        TABLE_PREFIX . TABLE_USERS . ".email",
      ])
        ->where(TABLE_PREFIX . TABLE_USERS . '.role_id', "=", 2)
        ->where(TABLE_PREFIX . TABLE_USERS . '.site_id', '=', DB::raw(TABLE_PREFIX . TABLE_SITES . ".id"))
        ->limit(1);

      $query->leftJoin(
        TABLE_PREFIX . TABLE_USERS,
        TABLE_PREFIX . TABLE_SITES . ".id",
        "=",
        TABLE_PREFIX . TABLE_USERS . ".site_id"
      )
        ->groupBy(TABLE_PREFIX . TABLE_SITES . ".id");

      $query->select([
        TABLE_PREFIX . TABLE_SITES . ".id",
        TABLE_PREFIX . TABLE_SITES . ".domain",
        TABLE_PREFIX . TABLE_SITES . ".is_root",
        TABLE_PREFIX . TABLE_SITES . ".is_active",
        TABLE_PREFIX . TABLE_SITES . ".updated_at",
        DB::raw("COUNT(" . TABLE_PREFIX . TABLE_USERS . ".id) as totalUsers"),
      ])
        ->select(DB::subQuery($subQuery, 'email'))
        ->limit($length)
        ->offset($start);

      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      //$this->resp->sql = $query;
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $res;
    $this->jsonecho();
  }
}
