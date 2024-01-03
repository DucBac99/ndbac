<?php

namespace Orders\Buff;

use Controller;
use DateTime;
use Input;
use DB;
use GraphQL;
use stdClass;
use ServerApi\CustomerServer;
use ProxyStatic\ProxyFB;
use ProxyStatic\Vitechcheap;
use ProxyTurn\Shoplike;
use ServerApi\TraoDoiSub;

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
      $Service->select('buff-' . $Route->params->service_name);
    }

    if (!$Service->isAvailable() || !$Service->get("is_public")) {
      header("HTTP/1.0 404 Not Found");
      exit;
    }

    if (Input::post("action") == "refund" || Input::get("action") == "refund") {
      $this->refund();
    } else if (Input::post("action") == "check_warranty" || Input::get("action") == "check_warranty") {
      $this->check_warranty();
    } else if (Input::post("action") == "get_comment") {
      $this->get_comment();
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

    $page = "buff/default";
    if (in_array($Service->get("idname"), ['buff-likepost'])) {
      $page = "buff/react";
    } else if ($Service->get("idname") == 'buff-comment') {
      $page = "buff/comment";
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
    $Order = Controller::model("Order");

    if ($Service->get("is_maintaince")) {
      $this->resp->msg = "Dịch vụ đang bảo trì. Hãy thử lại sau";
      $this->jsonecho();
    }

    // Check required fields
    $required_fields = ["seeding_uid", "order_amount", "server_id"];
    if ($Service->get("idname") ==  'buff-likepost') {
      $required_fields[] = "reaction_type";
    } else if ($Service->get("idname") ==  'buff-comment') {
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
        TABLE_PREFIX . TABLE_SERVERS . ".name",
        TABLE_PREFIX . TABLE_SERVERS . ".api_url",
        TABLE_PREFIX . TABLE_SERVERS . ".api_key",
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

    $Orders = DB::table(TABLE_PREFIX . TABLE_ORDERS)
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

    //check đơn comment
    if ($Service->get("idname") == 'buff-likepost') {
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
    } else if ($Service->get("idname") == 'buff-comment') {
      $comment_need = preg_split("/\\r\\n|\\r|\\n/", Input::post("comment_need"));
      $comment_need = array_filter($comment_need, fn ($value) => !is_null($value) && $value !== '');
      if (count($comment_need) < $order_amount) {
        $this->resp->msg = "Nội dung bình luận phải lớn hơn hoặc bằng số lượng mua";
        $this->jsonecho();
      }
      $Order->set('comment_need', '');
    }

    $start_num = -1;

    // Lấy thông tin từng uid bằng api facebook
    if ($Server->domain == $AuthSite->get("domain")) {
      // if ($Service->get("idname") == 'buff-likepost') {
      //   $info_post = $this->get_info_story($info_uid, $proxy);
      //   $start_num = $info_post->reaction_count;
      // } else if ($Service->get("idname") == 'buff-comment') {
      //   $info_post = $this->get_info_story($info_uid, $proxy);
      //   $start_num = $info_post->comment_count;
      // } else if ($Service->get("idname") == 'buff-member') {
      //   $info_group = $this->get_info_group($info_uid->id, $proxy);
      //   $start_num = $info_group->member_count;
      // } else if ($Service->get("idname") == 'buff-share') {
      //   $info_post = $this->get_info_story($info_uid, $proxy);
      //   $start_num = $info_post->share_count;
      // } else if ($Service->get("idname") == 'buff-likepage') {
      //   $info_page = $this->get_info_page($info_uid, $proxy);
      //   $start_num = $info_page->liker_count;
      // } else if ($Service->get("idname") == 'buff-follow') {
      //   $info_user = $this->get_info_user($info_uid, $proxy);
      //   $start_num = $info_user->follower_count;
      // }
    } else if ($Server->domain == "customer.sabommo.net") {
      $customer_sv = new CustomerServer($Server->api_url, $Server->api_key, $Server->api_user_id);

      $responseApi = $customer_sv->orderBuff(
        Input::post("seeding_uid"),
        $Service->get("idname"),
        $order_amount,
        $Order->get("reaction_type"),
        $Order->get("comment_need"),
      );

      if ($responseApi->result == false) {
        $this->resp->msg = $responseApi->msg;
        $this->jsonecho();
      }
      $start_num = $responseApi->start_num;
      $Order->set('source_id', $responseApi->order_id);
    } else if ($Server->domain == "traodoisub.com") {
      $traodoisub_sv = new TraoDoiSub($Server->api_key);
      $resp = $traodoisub_sv->login();
      if (!$resp->success) {
        $this->resp->msg = "Lỗi " . $Server->name;
        $this->jsonecho();
      }

      $source_id = strtoupper(readableRandomString(8));

      $responseApi = $traodoisub_sv->orderBuff(
        Input::post("seeding_uid"),
        $Service->get("idname"),
        $order_amount,
        $Order->get("reaction_type"),
        $Order->get("comment_need"),
        $source_id
      );

      if (strpos($responseApi, "Mua thành công") === false) {
        $this->resp->msg = $responseApi;
        $this->jsonecho();
      }

      $start_num = 0;
      $Order->set("source_id", $source_id);
    }

    $date_warranty = new \Moment\Moment('now', date_default_timezone_get());
    $date_warranty->addDays($Service->get("warranty"));

    $Order->set('seeding_uid', $info_uid->id)
      ->set('order_amount', $order_amount)
      ->set('real_amount', $order_amount)
      ->set('seeding_num', 0)
      ->set('is_vip', 0)
      ->set('group_id', '')
      ->set('start_num', $start_num)
      ->set('source_from', $Server->id)
      ->set('wwwURL', $info_uid->url)
      ->set('share_type', $info_uid->share_type)
      ->set('__typename', $info_uid->__typename)
      ->set('uid_post', $info_uid->node_id)
      ->set('status', $start_num == -1 ? 'PENDING' :  'RUNNING')
      ->set('note', Input::post("note") ? Input::post("note") : '')
      ->set('seeding_type', $Service->get("idname"))
      ->set('expired_warranty_at', $date_warranty->format("Y-m-d H:i:s"))
      ->set('user_id', $AuthUser->get("id"));

    $pdo = DB::pdo();
    $pdo->beginTransaction();

    try {
      $User = Controller::model("User", $AuthUser->get("id"));
      if (!$User->isAvailable()) {
        $this->resp->msg = "Người dùng không tồn tại.";
        $this->jsonecho();
      }

      $total = $price * $order_amount;
      $current = $User->get("balance");
      $balance = $current - $total;

      if ($balance < 0) {
        $this->resp->msg = "Số dư đã hết, hãy nạp thêm!";
        $this->jsonecho();
      }

      $User->set("balance", $balance)->save();
      $order_id = $Order->set('price', $total)->save();

      if ($Service->get("idname") == 'buff-comment') {
        DB::table(TABLE_PREFIX . TABLE_ORDER_COMMENTS)
          ->insert(array_map(function ($item) use ($order_id, $date_warranty) {
            return array(
              'order_id' => $order_id,
              'comment' => $item,
              'status' => 0,
              'expired_at' => $date_warranty->format("Y-m-d H:i:s")
            );
          }, $comment_need));
      }

      if ($start_num == -1) {
        // shell_exec('curl "' . APPURL . '/cron/check?seeding_uid=' . $info_uid->id . '&seeding_type=' . $Service->get("idname") . '&order_id=' . $order_id . '" &');
      }
      $this->resp->order_id = $order_id;
      $this->resp->start_num = $start_num;

      DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
        ->insert(array(
          "user_id" => $User->get("id"),
          "before" => $current,
          "money" => $total,
          "type" => "-",
          "after" => $balance,
          "type_code" => "ORDER",
          "seeding_type" => $Service->get("idname"),
          "seeding_uid" => $info_uid->id,
          "site_id" => $AuthSite->get("id"),
          "content" => "Thanh toán đơn hàng thành công #ORDER_" . $order_id,
          "date" => date("Y-m-d H:i:s")
        ));

      $pdo->commit();
    } catch (\Exception $ex) {
      $pdo->rollback();
      $Order->delete();
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
      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", $order_id)
        ->select([
          TABLE_PREFIX . TABLE_ORDERS . ".id",
          TABLE_PREFIX . TABLE_ORDERS . ".status",
          TABLE_PREFIX . TABLE_ORDERS . ".user_id",
          TABLE_PREFIX . TABLE_ORDERS . ".created_at",
          TABLE_PREFIX . TABLE_ORDERS . ".price",
          TABLE_PREFIX . TABLE_ORDERS . ".__typename",
          TABLE_PREFIX . TABLE_ORDERS . ".start_num",
          TABLE_PREFIX . TABLE_ORDERS . ".seeding_num",
          TABLE_PREFIX . TABLE_ORDERS . ".order_amount",
          TABLE_PREFIX . TABLE_ORDERS . ".wwwURL",
          TABLE_PREFIX . TABLE_ORDERS . ".uid_post",
          TABLE_PREFIX . TABLE_ORDERS . ".source_from",
          TABLE_PREFIX . TABLE_ORDERS . ".seeding_uid",
          TABLE_PREFIX . TABLE_ORDERS . ".seeding_type",
        ]);

      if (!$AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }
      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    if (count($res) == 0) {
      $this->resp->msg = "Order không tồn tại";
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
    } else if (!in_array($order->status, ['RUNNING', 'PENDING', 'PAUSED', 'HOLDING', 'ERROR'])) {
      $this->resp->msg = "Không thể hoàn với đơn chưa running";
      $this->jsonecho();
    }

    // $pdo = DB::pdo();
    // $pdo->beginTransaction();
    try {
      if ($AuthUser->get("id") != $order->user_id) {
        $User = Controller::model("User", $order->user_id);
      } else {
        $User = $AuthUser;
      }

      if ($order->status == "PENDING") {
        $refund = $order->price;
        $real_seeding_num = 0;
      } else {
        $refund = 0;
        // thuat toan refund
        $start = intval($order->start_num);
        $order_amount = intval($order->order_amount);

        $checkLog = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
          ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".order_id", "=", $order_id)
          ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".status", "=", 1)
          ->select([
            DB::raw("COUNT(" . TABLE_PREFIX . TABLE_ORDER_LOGS . ".id) as total_log"),
          ])
          ->get();
        $real_seeding_num = intval($checkLog[0]->total_log);

        // bắt đầu xử lý
        $remain = $order_amount - $real_seeding_num;
        if ($remain > 0) {
          $refund = (int)(($order->price * $remain) / $order_amount);
        }
      }
      $this->resp->udpate = 1;
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", $order_id)
        ->update([
          'status' => 'REFUND',
          'seeding_num' => $real_seeding_num,
          'updated_at' => date("Y-m-d H:i:s")
        ]);

      $current = $User->get("balance");
      $balance = $User->get("balance") + $refund;

      if ($AuthUser->get("id") == $User->get("id") || !$User->isAdmin()) {
        DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
          ->insert(array(
            "user_id" => $User->get("id"),
            "before" => $current,
            "money" => $refund,
            "type" => "+",
            "after" => $balance,
            "type_code" => "REFUND_ORDER",
            "seeding_type" => $order->seeding_type,
            "seeding_uid" => $order->seeding_uid,
            "site_id" => $User->get("site_id"),
            "content" => "Hoàn đơn thành công #REFUND_ORDER_" . $order_id,
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
            "type_code" => "ADMIN_REFUND_ORDER",
            "seeding_type" => $order->seeding_type,
            "seeding_uid" => $order->seeding_uid,
            "site_id" => $User->get("site_id"),
            "content" => "Hoàn đơn thành công #ADMIN_REFUND_ORDER_" . $order_id,
            'date' => date("Y-m-d H:i:s")
          ));
      }

      $User->set("balance", $balance)->save();
      // $pdo->commit();
    } catch (\Exception $ex) {
      // $pdo->rollback();
      $this->resp->msg = "Lỗi hệ thống! Hãy thử lại. ";
      $this->resp->error = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->msg = "Hoàn tiền đơn thành công";
    $this->jsonecho();
  }

  protected function check_warranty()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    $order_id = Input::post("id");
    if (!$order_id) {
      $order_id = Input::get("order_id");
    }

    if (!$order_id) {
      $this->resp->msg = "Thiếu order_id";
      $this->jsonecho();
    }

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", $order_id)
        ->select([
          TABLE_PREFIX . TABLE_ORDERS . ".id",
          TABLE_PREFIX . TABLE_ORDERS . ".status",
          TABLE_PREFIX . TABLE_ORDERS . ".expired_warranty_at",
        ]);

      if (!$AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }
      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    if (count($res) == 0) {
      $this->resp->msg = "Order không tồn tại";
      $this->jsonecho();
    }

    $order = $res[0];

    if ($order->status != "COMPLETED") {
      $this->resp->msg = "Không thể bảo hành khi đơn chưa COMPLETED";
      $this->jsonecho();
    }

    $ed = new DateTime($order->expired_warranty_at);
    $now = new DateTime();

    if ($ed < $now) {
      $this->resp->msg = "Đơn đã hết hạn bảo hành";
      $this->jsonecho();
    }

    DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where("id", "=", $order->id)
      ->update([
        "status" => "CHECKING_WARRANTY",
        'updated_at' => date("Y-m-d H:i:s")
      ]);

    $this->resp->result = 1;
    $this->resp->reload_table = 1;
    $this->resp->msg = "Đã xử lý bảo hành thành công";
    $this->jsonecho();
  }

  protected function get_comment()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    $order_id = Input::post("id");
    if (!$order_id) {
      $order_id = Input::get("order_id");
    }

    if (!$order_id) {
      $this->resp->msg = "Thiếu order_id";
      $this->jsonecho();
    }

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", $order_id)
        ->select([
          TABLE_PREFIX . TABLE_ORDERS . ".id",
        ]);

      if (!$AuthUser->isAdmin()) {
        $query->where(TABLE_PREFIX . TABLE_ORDERS . ".user_id", "=", $AuthUser->get("id"));
      }
      $res = $query->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    if (count($res) == 0) {
      $this->resp->msg = "Order không tồn tại";
      $this->jsonecho();
    }

    $order = $res[0];

    try {
      $resp = DB::table(TABLE_PREFIX . TABLE_ORDER_COMMENTS)
        ->where(TABLE_PREFIX . TABLE_ORDER_COMMENTS . ".order_id", "=", $order->id)
        ->select([
          TABLE_PREFIX . TABLE_ORDER_COMMENTS . ".comment",
        ])
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = 1;
    $this->resp->data = $resp;
    $this->resp->msg = "Đã lấy comment thành công";
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

  public function get_info_group($group_id, $proxy)
  {
    $check = GraphQL::get_info_group($group_id, $proxy);
    if (!$check->result) {
      $this->resp = $check;
      $this->jsonecho();
    }

    return $check;
  }

  public function get_info_story($info_uid, $proxy)
  {
    $check = GraphQL::get_info_story($info_uid->id, $info_uid->node_id, $info_uid->url, $proxy);
    if (!$check->result) {
      $this->resp = $check;
      $this->jsonecho();
    }
    return $check;
  }

  public function get_info_page($info_uid, $proxy)
  {
    $check = GraphQL::get_info_page($info_uid->id, $proxy);
    if (!$check->result) {
      $this->resp = $check;
      $this->jsonecho();
    }
    return $check;
  }

  public function get_info_user($info_uid, $proxy)
  {
    $check = GraphQL::get_info_user($info_uid->id, $info_uid->__typename, $proxy);
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
