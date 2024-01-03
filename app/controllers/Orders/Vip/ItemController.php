<?php

namespace Orders\Vip;

use Controller;
use Input;
use DB;
use GraphQL;
use stdClass;
use ServerApi\CustomerServer;
use ServerApi\TraoDoiSub;
use ProxyStatic\ProxyFB;
use ProxyStatic\Vitechcheap;
use ProxyTurn\Shoplike;

/**
 * Item Controller
 */
class ItemController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");
    $Route = $this->getVariable("Route");
    $AuthSite = $this->getVariable("AuthSite");

    // Auth
    if (!$AuthUser) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $Service = Controller::model("Service");
    if (isset($Route->params->service_name)) {
      $Service->select('vip-' . $Route->params->service_name);
    }

    if (!$Service->isAvailable() || !$Service->get("is_public")) {
      header("HTTP/1.0 404 Not Found");
      exit;
    }

    if (Input::post("action") == "refund"  || Input::get("action") == "refund") {
      $this->refund();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $ServersDB = DB::table(TABLE_PREFIX . TABLE_SERVICE_SETTINGS)
        ->leftJoin(
          TABLE_PREFIX . TABLE_SERVERS,
          TABLE_PREFIX . TABLE_SERVERS . ".id",
          "=",
          TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".server_id"
        )
        ->where(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".service_id", "=", $Service->get("id"))
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

      $Servers = new stdClass;
      foreach ($ServersDB as $sv) {
        $Servers->{'sv_id_' . $sv->id} = new stdClass;
        $sv->price = json_decode($sv->price);
        $sv->amount = json_decode($sv->amount);
        $sv->options = json_decode($sv->options);
        $Servers->{'sv_id_' . $sv->id} = $sv;
      }
      $this->setVariable("Servers", $Servers);
    }

    if ($Service->get("idname") == 'vip-like') {
      $page = 'vip/react';
    } else if ($Service->get("idname") == 'vip-comment') {
      $page = 'vip/comment';
    } else if (strpos($Service->get("idname"), 'vip') !== false) {
      $page = 'vip/default';
    }

    $this->setVariable("page", $page)
      ->setVariable("Service", $Service);

    if (Input::post("action") == "save") {
      $this->save();
    }
    $this->view("orders/item");
  }

  protected function save()
  {
    $this->resp->result = 0;
    if (get_option("CLOSE_ORDER_ALL_SERVICES")) {
      $this->resp->msg = "Hệ thống tạm bảo trì toàn bộ dịch vụ! Hãy thử lại sau";
      $this->jsonecho();
    }
    $AuthUser = $this->getVariable("AuthUser");
    $Service = $this->getVariable("Service");
    $AuthSite = $this->getVariable("AuthSite");
    $Order = Controller::model("VipOrder");

    if ($Service->get("is_maintaince")) {
      $this->resp->msg = "Dịch vụ đang bảo trì. Hãy thử lại sau";
      $this->jsonecho();
    }

    // Check required fields
    $required_fields = ["seeding_uid", "month", "server_id"];
    if ($Service->get("idname") == 'vip-like') {
      $required_fields[] = "reaction_type";
    } else if ($Service->get("idname") ==  'vip-comment') {
      $required_fields[] = "comment_need";
    }

    foreach ($required_fields as $field) {
      if (!Input::post($field)) {
        $this->resp->msg = "Vui lòng nhập đầy đủ thông tin";
        $this->resp->novalidate = true;
        $this->jsonecho();
      }
    }

    if (filter_var(Input::post("seeding_uid"), FILTER_VALIDATE_URL) !== FALSE) {
      $this->resp->msg = "Hãy nhập ID thay cho liên kết!";
      $this->jsonecho();
    }

    $server_id = Input::post("server_id");

    // Check server hợp lệ không
    $checkServer = DB::table(TABLE_PREFIX . TABLE_SERVERS)
      ->leftJoin(
        TABLE_PREFIX . TABLE_SERVICE_SETTINGS,
        function ($qb) use ($Service, $AuthSite) {
          $qb->on(TABLE_PREFIX . TABLE_SERVERS . ".id", "=", TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".server_id");
          $qb->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".service_id", "=", DB::raw($Service->get("id")));
          $qb->on(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".site_id", "=", DB::raw($AuthSite->get("id")));
        }
      )
      ->where(TABLE_PREFIX . TABLE_SERVERS . ".id", "=", $server_id)
      ->select([
        TABLE_PREFIX . TABLE_SERVERS . ".id",
        TABLE_PREFIX . TABLE_SERVERS . ".is_public",
        TABLE_PREFIX . TABLE_SERVERS . ".api_url",
        TABLE_PREFIX . TABLE_SERVERS . ".api_key",
        TABLE_PREFIX . TABLE_SERVERS . ".name",
        TABLE_PREFIX . TABLE_SERVERS . ".api_user_id",
      ])
      ->select([
        TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".price",
        TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".amount",
        TABLE_PREFIX . TABLE_SERVICE_SETTINGS . ".options",
      ])
      ->get();

    if (count($checkServer) == 0 || !$checkServer[0]->is_public) {
      $this->resp->msg = "Máy chủ không tồn tại!";
      $this->jsonecho();
    }

    $Server = $checkServer[0];
    $Server->domain = getHost($Server->api_url);
    $Server->options = json_decode($Server->options);
    $Server->price = json_decode($Server->price);
    $Server->amount = json_decode($Server->amount);

    if (!empty($Server->options->public) && $Server->options->public == false) {
      $this->resp->msg = "Máy chủ hiện tại không hỗ trợ dịch vụ này!";
      $this->jsonecho();
    }

    if (!empty($Server->options->maintain) && $Server->options->maintain == true) {
      $this->resp->msg = "Máy chủ đang bảo trì!";
      $this->jsonecho();
    }

    // Lấy thông ting giá từ json db
    $price = get_real_price($Server->price, $server_id, $AuthUser->get('idname'));
    $amount = get_real_amount($Server->amount, $server_id, $AuthUser->get('idname'));

    $order_amount = intval(Input::post("order_amount"));
    if ($order_amount > $amount->max) {
      $this->resp->msg = "Số lượng tối đa có thể mua là " . $amount->max;
      $this->jsonecho();
    } else if ($order_amount < $amount->min) {
      $this->resp->msg = "Số lượng tối thiểu có thể mua là " . $amount->min;
      $this->jsonecho();
    }

    $month = intval(Input::post("month"));
    if ($month < 1) {
      $this->resp->msg = "Số tháng phải là số dương";
      $this->jsonecho();
    }

    // $proxy = $this->getProxy();
    // $info_uid = $this->check_uid(Input::post("seeding_uid"), $Service->get("idname"), $proxy);
    if (!is_numeric(Input::post("seeding_uid"))) {
      $this->resp->msg = "Hãy nhập ID dạng số!";
      $this->jsonecho();
    }

    $length = strlen(Input::post("seeding_uid"));
    if ($length < 6 || $length > 20) {
      $this->resp->msg = "Seeding UID không đúng!";
      $this->jsonecho();
    }
    $info_uid = new stdClass;
    $info_uid->id = Input::post("seeding_uid");
    $info_uid->url = "";
    $info_uid->share_type = 0;
    $info_uid->__typename = '';
    $info_uid->node_id = '';

    // if (!in_array($info_uid->__typename, ['User', 'Page', 'PageProfile'])) {
    //   $this->resp->msg = "Tính năng chỉ áp dụng cho các ID thuộc nhóm User, Page, PageProfile!";
    //   $this->jsonecho();
    // }

    $Orders = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
      ->where('user_id', '=', $AuthUser->get("id"))
      ->where('seeding_uid', '=', $info_uid->id)
      ->where('seeding_type', '=', $Service->get("idname"))
      ->whereNotIn('status', ["COMPLETED", "REFUND"])
      ->select([
        DB::raw("COUNT(*) as total")
      ])
      ->get();

    if ($Orders[0]->total > 0) {
      $this->resp->msg = "ID này đang có một đơn hàng chưa xử lý";
      $this->jsonecho();
    }

    if ($Service->get("idname") == 'vip-like') {
      $reactions = Input::post("reaction_type");
      if (!is_array($reactions)) {
        $reactions = explode("|", Input::post("reaction_type"));
      }

      foreach ($reactions as $reaction) {
        if (!in_array($reaction, ['LIKE', 'LOVE', 'HAHA', 'WOW', 'SAD', 'CARE', 'ANGRY'])) {
          $this->resp->msg = "Reaction Types không hợp lệ";
          $this->jsonecho();
        }
      }
      $Order->set('reaction_type', join("|", $reactions));
    } else if ($Service->get("idname") == 'vip-comment') {
      $comment_need = preg_split("/\\r\\n|\\r|\\n/", Input::post("comment_need"));
      $comment_need = array_filter($comment_need, fn ($value) => !is_null($value) && $value !== '');
      $Order->set('comment_need', join("\n", $comment_need));
    }

    if ($Server->domain == "customer.sabommo.net") {
      $customer_sv = new CustomerServer($Server->api_url, $Server->api_key, $Server->api_user_id);

      $responseApi = $customer_sv->orderVip(
        Input::post("seeding_uid"),
        $Service->get("idname"),
        $order_amount,
        $month,
        $Order->get("reaction_type"),
        $Order->get("comment_need"),
      );

      if ($responseApi->result == false) {
        $this->resp->msg = $responseApi->msg;
        $this->jsonecho();
      }
      $Order->set('source_id', $responseApi->order_id);
    } else if ($Server->domain == "traodoisub.com") {
      $traodoisub_sv = new TraoDoiSub($Server->api_key);

      $list_packet = [
        50, 100, 150, 200, 300, 400, 500, 600, 700, 800, 900, 900, 1000, 1500, 2000
      ];
      if (!in_array($order_amount, $list_packet)) {
        $this->resp->msg = $Server->name . " chỉ hỗ trợ các gói like như sau: " . join(", ", $list_packet);
        $this->jsonecho();
      }

      $list_month = [
        1, 2, 3
      ];
      if (!in_array($month, $list_month)) {
        $this->resp->msg = $Server->name . " chỉ hỗ trợ các tháng như sau: " . join(", ", $list_month);
        $this->jsonecho();
      }

      $resp = $traodoisub_sv->login();
      if (!$resp->success) {
        $this->resp->msg = "Lỗi " . $Server->name;
        $this->jsonecho();
      }

      $source_id = strtoupper(readableRandomString(8));

      $responseApi = $traodoisub_sv->orderVip(
        Input::post("seeding_uid"),
        $Service->get("idname"),
        $order_amount,
        $month * 30,
        $source_id
      );

      if (strpos($responseApi, "Mua thành công") === false) {
        $this->resp->msg = $responseApi;
        $this->jsonecho();
      }

      $Order->set("source_id", $source_id);
    }

    $date = new \Moment\Moment('now', date_default_timezone_get());
    $date->addMonths($month);

    $Order->set('seeding_uid', $info_uid->id)
      ->set('month', $month)
      ->set('order_amount', $order_amount)
      ->set('real_amount', $order_amount)
      ->set('wwwURL', $info_uid->url)
      ->set('__typename', $info_uid->__typename)
      ->set('uid_post', $info_uid->node_id)
      ->set('source_from', $Server->id)
      ->set('status', 'RUNNING')
      ->set('note', Input::post("note") ? Input::post("note") : '')
      ->set('seeding_type', $Service->get("idname"))
      ->set('expired_at', $date->format("Y-m-d H:i:s"))
      ->set('user_id', $AuthUser->get("id"));

    $pdo = DB::pdo();
    $pdo->beginTransaction();

    try {
      $total = $price * $order_amount * $month;
      $current = $AuthUser->get("balance");
      $balance = $current - $total;

      if ($balance < 0) {
        $this->resp->msg = "Số dư đã hết, hãy nạp thêm!";
        $this->jsonecho();
      }

      $AuthUser->set("balance", $balance)->save();
      $order_id = $Order->set('price', $total)->save();

      $this->resp->order_id = $order_id;


      DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->insert(array(
          "user_id" => $AuthUser->get("id"),
          "before" => $current,
          "money" => $total,
          "type" => "-",
          "after" => $balance,
          "type_code" => "ORDER",
          "seeding_type" => $Service->get("idname"),
          "seeding_uid" => $info_uid->id,
          "site_id" => $AuthSite->get("id"),
          "content" => "Thanh toán đơn hàng thành công #VIP_ORDER_" . $order_id,
          'date' => date("Y-m-d H:i:s")
        ));

      $pdo->commit();
    } catch (\Exception $ex) {
      $pdo->rollback();
      $this->resp->msg = "Lỗi hệ thống! Hãy thử lại. ";
      $this->resp->error = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->seeding_uid = $info_uid->id;
    $this->resp->msg = "Đã thêm đơn thành công!";
    $this->jsonecho();
  }

  protected function refund()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");
    $AuthSite = $this->getVariable("AuthSite");

    $order_id = Input::post("id");
    if (!$order_id) {
      $order_id = Input::get("order_id");
    }

    if (!$order_id) {
      $this->resp->msg = "Thiếu order_id";
      $this->jsonecho();
    }

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", "=", $order_id)
        ->select([
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".id",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".status",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".seeding_type",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".seeding_uid",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".created_at",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".expired_at",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".source_from",
          TABLE_PREFIX . TABLE_VIP_ORDERS . ".price",
        ]);

      if (!$AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }
      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    if (count($res) == 0) {
      $this->resp->msg = "Vip Order không tồn tại";
      $this->jsonecho();
    }

    $order = $res[0];

    $Server = Controller::model("Server", $order->source_from);
    if ($Server->isAvailable() && !$Server->get("allow_refund")) {
      $this->resp->msg = $Server->get("name") . " không phép hoàn đơn.";
      $this->jsonecho();
    }

    if ($order->status == 'REFUND') {
      $this->resp->msg = "Đơn đã hoàn";
      $this->jsonecho();
    } else if (!in_array($order->status, ['RUNNING', 'PENDING', 'PAUSED'])) {
      $this->resp->msg = "Không thể hoàn với đơn với trạng thái hiện tại";
      $this->jsonecho();
    }

    $pdo = DB::pdo();
    $pdo->beginTransaction();
    try {
      if ($AuthUser->get("id") != $order->user_id) {
        $User = Controller::model("User", $order->user_id);
      } else {
        $User = $AuthUser;
      }

      $refund = 0;
      $start = new \Moment\Moment($order->created_at, date_default_timezone_get());
      $end = $start->from($order->expired_at);

      $now = new \Moment\Moment("now", date_default_timezone_get());
      $remain = $now->from($order->expired_at);
      if ($remain->getDays() > 0) {
        $refund = round(($order->price * $remain->getDays()) / $end->getDays());
      }

      DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".id", "=", $order_id)
        ->where(TABLE_PREFIX . TABLE_VIP_ORDERS . ".user_id", "=", $User->get("id"))
        ->update([
          'status' => 'REFUND',
          'updated_at' => date("Y-m-d H:i:s")
        ]);

      $current = $User->get("balance");
      $balance = $User->get("balance") + $refund;

      if ($AuthUser->get("id") == $User->get("id")) {
        DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
          ->insert(array(
            "user_id" => $User->get("id"),
            "before" => $current,
            "money" => $refund,
            "type" => "+",
            "after" => $balance,
            "type_code" => "REFUND_VIP_ORDER",
            "seeding_type" => $order->seeding_type,
            "seeding_uid" => $order->seeding_uid,
            "site_id" => $User->get("site_id"),
            "content" => "Hoàn đơn thành công #REFUND_VIP_ORDER_" . $order_id,
            'date' => date("Y-m-d H:i:s")
          ));
      } else {
        DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
          ->insert(array(
            "user_id" => $User->get("id"),
            "before" => $current,
            "money" => $refund,
            "type" => "+",
            "after" => $balance,
            "type_code" => "ADMIN_REFUND_VIP_ORDER",
            "seeding_type" => $order->seeding_type,
            "seeding_uid" => $order->seeding_uid,
            "site_id" => $User->get("site_id"),
            "content" => "Hoàn đơn thành công #ADMIN_REFUND_VIP_ORDER_" . $order_id,
            'date' => date("Y-m-d H:i:s")
          ));
      }

      $User->set("balance", $balance)->save();
      $pdo->commit();
    } catch (\Exception $ex) {
      $pdo->rollback();
      $this->resp->msg = "Lỗi hệ thống! Hãy thử lại. ";
      $this->resp->error = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->msg = "Hoàn tiền đơn thành công";
    $this->jsonecho();
  }

  public function check_uid($url, $idname, $proxy)
  {
    $check = GraphQL::get_uid($url, $idname, $proxy);
    if (!$check->result) {
      $this->resp = $check;
      $this->jsonecho();
    }

    return $check;
  }

  protected function getProxy()
  {
    $type_proxy = get_option("type_proxy_for_order");
    if ($type_proxy == "proxyfb") {
      $proxyfb = new ProxyFB(json_decode(get_option("list_proxy_static_proxyfb"), true));
      $proxy = $proxyfb->getProxy();
    } else if ($type_proxy == "vitechcheap") {
      $vitechcheap = new Vitechcheap(json_decode(get_option("list_proxy_static_vitechcheap"), true));
      $proxy = $vitechcheap->getProxy();
    } else if ($type_proxy == "shoplike") {
      $shoplike = new Shoplike(json_decode(get_option("list_key_proxy_shoplike"), true));
      $proxy = $shoplike->getNewProxy();
      if (!$proxy) {
        $proxy = $shoplike->getCurrentProxy();
      };
    }
    return $proxy;
  }
}
