<?php

namespace API_Private;

use Controller;
use Input;
use DB;
use Redis;
use stdClass;

/**
 * BuffOrders Controller
 */
class BuffOrdersController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $token = Input::get("access_token");
    if (!$token || $token != TOKEN_BUFF_ORDERS) {
      $this->resp->result = false;
      $this->resp->msg = "Thiếu token xác thực";
      $this->jsonecho();
    }

    require_once(APPPATH . '/inc/order-status.inc.php');
    $this->setVariable("order_status", $order_status);

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
      $this->getApi();
    } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    }
  }

  private function getApi()
  {
    $action = Input::get("action");
    switch ($action) {
      case 'update-buff-order':
        $this->updateBuffOrder();
        break;
      case 'update-name-buff-order':
        $this->updateNameBuffOrder();
        break;
      case 'report-buff-order':
        $this->reportBuffOrder();
        break;
      case 'list-buff-order':
        $this->listBuffOrder();
        break;
      case 'list-no-name-buff-order':
        $this->listNoNameBuffOrder();
        break;
      case 'list-buff-order-warranty':
      case 'list-buff-order-pending':
        $this->listBuffOrderbyStatus(['PENDING', 'CHECKING_WARRANTY']);
        break;
      case 'update-buff-order-pending':
        $this->updateBuffOrderPending();
        break;
      case 'update-buff-order-warranty':
        $this->updateBuffOrderWarranty();
        break;
      case 'get-comment-buff-order':
        $this->getCommentBuffOrder();
        break;
      case 'reset-all-comment-buff-order':
        $this->resetAllCommentBuffOrder();
        break;
      case 'reset-comment-buff-order':
        $this->resetCommentBuffOrder();
        break;
      case 'check-valid-order':
        $this->checkValidOrder();
        break;
      case 'check-report-order':
        $this->checkReportOrder();
        break;
      case 'count-pending-order':
        $this->countPendingOrder();
        break;
      default:
        # code...
        break;
    }
  }

  private function updateBuffOrder()
  {
    $order_status = $this->getVariable("order_status");
    $this->resp->result = false;

    $required_fields = ["order_id", "status", "note"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    $status = Input::get("status");

    if (!$status || !in_array($status, $order_status)) {
      $this->resp->msg = "Thiếu status";
      $this->jsonecho();
    }

    $orders = DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where('id', "=", Input::get("order_id"))
      ->select(["id", "status"])
      ->get();

    if (count($orders) == 0) {
      $this->resp->msg = "Invalid Order!";
      $this->jsonecho();
    }

    $order = $orders[0];

    if ($order->status == "REFUND") {
      $this->resp->msg = "Đơn đã hoàn!";
      $this->jsonecho();
    }

    if ($order->status != "RUNNING") {
      $this->resp->msg = "Đơn không chạy!";
      $this->jsonecho();
    }

    if ($status == "RUNNING") {
      DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
        ->where('order_id', "=", $order->id)
        ->where('status', "=", 0)
        ->update(array(
          "status" => 2,
        ));
    }

    $dataUpdate = array(
      'status' => $status,
      'note_extra' => Input::get("note") ? Input::get("note") : '',
      'updated_at' => date("Y-m-d H:i:s"),
    );

    if (Input::post("start_num")) {
      $dataUpdate['start_num'] = intval(Input::get("start_num"));
    }
    if (Input::post("seeding_num")) {
      $dataUpdate['seeding_num'] = intval(Input::get("seeding_num"));
    }

    DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where('id', "=", Input::get("order_id"))
      ->update($dataUpdate);

    $this->resp->result = true;
    $this->resp->msg = "update job thành công";
    $this->jsonecho();
  }

  private function updateNameBuffOrder()
  {
    $this->resp->result = false;
    $required_fields = ["order_id", "name"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    $dataUpdate = array(
      'name' => Input::get("name"),
      'updated_at' => date("Y-m-d H:i:s"),
    );

    DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where('id', "=", Input::get("order_id"))
      ->update($dataUpdate);

    $this->resp->result = true;
    $this->resp->msg = "update name job thành công";
    $this->jsonecho();
  }

  private function reportBuffOrder()
  {
    $this->resp->result = false;

    $required_fields = ["seeding_uid", "seeding_type", "account_uid", "order_id", "user_id"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    $orders = DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where('id', "=", Input::get("order_id"))
      ->where('status', "=", "RUNNING")
      ->select(["id"])
      ->get();

    if (count($orders) == 0) {
      $this->resp->msg = "Invalid Order!";
      $this->jsonecho();
    }

    $users = DB::table(TABLE_PREFIX . TABLE_USERS)
      ->where('id', "=", Input::get("user_id"))
      ->select(["id"])
      ->get();

    if (count($users) == 0) {
      $this->resp->msg = "Invalid User!";
      $this->jsonecho();
    }
    $group_id = Input::get("group_id");
    if (!$group_id) {
      $group_id = "";
    }

    $status = intval(Input::get("status")) == 1 ? 1 : 0;

    try {
      $checkStatusFail = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
        ->where('order_id', "=", Input::get("order_id"))
        ->where('status', "=", 0)
        ->select([
          DB::raw("COUNT(*) as total")
        ])
        ->get();

      $max_status_pause = intval(get_option("MAX_STATUS_PAUSE"));
      if ($checkStatusFail[0]->total >= $max_status_pause) {
        DB::table(TABLE_PREFIX . TABLE_ORDERS)
          ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", Input::get("order_id"))
          ->update(array(
            'status' => "PAUSED",
            'note_extra' => "auto paused",
            'updated_at' => date("Y-m-d H:i:s"),
          ));
      }
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    try {
      $check = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
        ->where('seeding_uid', "=", Input::get("seeding_uid"))
        ->where('seeding_type', "=", Input::get("seeding_type"))
        ->where('account_uid', "=", Input::get("account_uid"))
        ->select([
          TABLE_PREFIX . TABLE_ORDER_LOGS . ".id",
          TABLE_PREFIX . TABLE_ORDER_LOGS . ".status"
        ])
        ->get();
      if (count($check) == 0) {
        $this->resp->report = true;
        $this->addRedis(Input::get("user_id"), Input::get("seeding_type"));
        DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
          ->insert(array(
            'user_id' => Input::get("user_id"),
            'seeding_uid' => Input::get("seeding_uid"),
            'order_id' => Input::get("order_id"),
            'seeding_type' => Input::get("seeding_type"),
            'account_uid' => Input::get("account_uid"),
            'status' => $status,
            'group_id' => $group_id,
            'updated_at' => date("Y-m-d H:i:s"),
            'created_at' => date("Y-m-d H:i:s"),
          ));
      } else if ($check[0]->status != Input::get("status")) {
        DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
          ->where(TABLE_PREFIX . TABLE_ORDER_LOGS . ".id", "=", $check[0]->id)
          ->update(array(
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s"),
          ));
      } else {
        $this->resp->msg = "report đã tồn tại";
        $this->jsonecho();
      }
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }
    $this->resp->result = true;
    $this->resp->msg = "report thành công";
    $this->jsonecho();
  }

  private function listBuffOrder()
  {
    $this->resp->result = false;

    $required_fields = ["account_uid", "limit", "type_orders"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    $type_orders = explode("|", Input::get("type_orders"));
    $sql = "";
    $length_type_orders = count($type_orders);
    $each_limit = intval(Input::get("limit"));
    for ($i = 0; $i < $length_type_orders; $i++) {
      $seeding_type = $type_orders[$i];
      if ($seeding_type != '') {
        $sql = $sql . $this->genSqlOrders($seeding_type, Input::get("account_uid"), $each_limit);
        if ($i < $length_type_orders - 1) {
          $sql = $sql . "
                      UNION DISTINCT
                  ";
        }
      }
    }

    $result = DB::query($sql)->get();
    $data = [];
    foreach ($result as $row) {
      $datas['id']            = $row->id;
      $datas['seeding_uid']   = $row->seeding_uid;
      if (in_array($row->seeding_type, ["buff-likepost", "buff-comment", "buff-share"])) {
        $datas['uid_post']  = $row->uid_post;
        $datas['wwwURL']  = $row->wwwURL;
      }
      $datas['seeding_type'] = $row->seeding_type;
      $datas['order_amount'] = $row->order_amount;
      $datas['share_type'] = $row->share_type;
      $datas['feedback_id'] = base64_encode("feedback:" . $row->seeding_uid);

      if ($row->seeding_type == "buff-likepost") {
        $datas['reaction_type'] = $row->reaction_type;
      }

      $datas['comment_need'] = '';
      $datas['name'] = $row->name;
      $datas['created_at'] = $row->created_at;
      $data[] = $datas;
    }
    $this->resp = $data;
    $this->jsonecho();
  }

  private function listNoNameBuffOrder()
  {
    $this->resp->result = false;

    $required_fields = ["account_uid", "limit", "type_orders"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    $type_orders = explode("|", Input::get("type_orders"));
    $sql = "";
    $length_type_orders = count($type_orders);
    $each_limit = intval(Input::get("limit"));

    for ($i = 0; $i < $length_type_orders; $i++) {
      $seeding_type = $type_orders[$i];
      if ($seeding_type != '') {
        $sql = $sql . $this->genSqlNoNameOrders($seeding_type, Input::get("account_uid"), $each_limit);
        if ($i < $length_type_orders - 1) {
          $sql = $sql . "
                      UNION DISTINCT
                  ";
        }
      }
    }

    $result = DB::query($sql)->get();
    $data = [];
    foreach ($result as $row) {
      $datas['id']            = $row->id;
      $datas['seeding_uid']   = $row->seeding_uid;
      if (in_array($row->seeding_type, ["buff-likepost", "buff-comment", "buff-share"])) {
        $datas['uid_post']  = $row->uid_post;
        $datas['wwwURL']  = $row->wwwURL;
      }
      $datas['seeding_type'] = $row->seeding_type;
      $datas['order_amount'] = $row->order_amount;
      $datas['share_type'] = $row->share_type;

      $datas['feedback_id'] = base64_encode("feedback:" . $row->seeding_uid);
      if ($row->seeding_type == "buff-likepost") {
        $datas['reaction_type'] = $row->reaction_type;
      }

      $datas['comment_need'] = '';
      $datas['created_at'] = $row->created_at;
      $data[] = $datas;
    }
    $this->resp = $data;
    $this->jsonecho();
  }

  private function genSqlOrders($seeding_type, $account_uid, $limit)
  {
    $comment_condition = "";


    $condition_seeding_type = strpos($seeding_type, 'follow') !== false ? 'buff-follow' : (strpos($seeding_type, 'likepage') !== false ? 'buff-likepage' : $seeding_type);

    $query = DB::query("SELECT MIN(id) AS minID, MAX(id) AS maxID FROM tb_orders  WHERE seeding_type = '$seeding_type' AND status = 'RUNNING'")->get();
    $min = intval($query[0]->minID);
    $max = intval($query[0]->maxID);
    $rand_num = rand($min, $max) - $limit;

    $sort = "AND od.id >= $rand_num";
    //$sort = "ORDER BY id RAND()";
    //$sort = "order by id asc";
    $sort = "order by id desc";
    return "
                (
                    SELECT
                        od.*
                    FROM
                        " . TABLE_PREFIX . TABLE_ORDERS . " od
                    WHERE
                        seeding_type = '$seeding_type'
                        AND status = 'RUNNING'
                        AND group_id = ''
                        AND NOT EXISTS (
                            SELECT
                                *
                            FROM
                                " . TABLE_PREFIX . TABLE_ORDER_LOGS . " lg1
                            WHERE
                                lg1.account_uid = '$account_uid'
                                AND lg1.seeding_uid = od.seeding_uid
                                AND lg1.seeding_type LIKE '$condition_seeding_type%'
                        )
                        $comment_condition
                        $sort
                        LIMIT $limit
                )";
  }

  private function genSqlNoNameOrders($seeding_type, $account_uid, $limit)
  {
    $comment_condition = "";

    // $sort = "ORDER BY RAND()";
    $sort = "";

    $condition_seeding_type = strpos($seeding_type, 'follow') !== false ? 'buff-follow' : (strpos($seeding_type, 'likepage') !== false ? 'buff-likepage' : $seeding_type);

    return "
                (
                    SELECT
                        od.*
                    FROM
                        " . TABLE_PREFIX . TABLE_ORDERS . " od
                    WHERE
                        seeding_type = '$seeding_type'
                        AND status = 'RUNNING'
                        AND group_id = ''
                        AND name = ''
                        AND NOT EXISTS (
                            SELECT
                                *
                            FROM
                                " . TABLE_PREFIX . TABLE_ORDER_LOGS . " lg1
                            WHERE
                                lg1.account_uid = '$account_uid'
                                AND lg1.seeding_uid = od.seeding_uid
                                AND lg1.seeding_type LIKE '$condition_seeding_type%'
                        )
                        $comment_condition
                    $sort
                    LIMIT $limit
                )";
  }

  private function checkValidOrder()
  {
    $this->resp->result = false;

    $required_fields = ["order_id"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    $orders = DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where('id', "=", Input::get("order_id"))
      ->select(["id", "seeding_uid", "seeding_type", "status", "real_amount", "order_amount", "start_num"])
      ->get();

    if (count($orders) == 0) {
      $this->resp->msg = "Invalid Order!";
      $this->jsonecho();
    }
    $order = $orders[0];

    if ($order->status == "CHECKING_WARRANTY") {
      $this->resp->msg = "Failed! Running Is check warranty!";
      $this->jsonecho();
    } else if ($order->status == "COMPLETED") {
      $this->resp->msg = "Failed! Not Running Order!";
      $this->jsonecho();
    }

    $date = new \Moment\Moment('now', 'Asia/Ho_Chi_Minh');
    $day_start = $date->cloning()->startOf('day');
    $day_end = $date->cloning()->endOf('day');


    $result = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
      ->where('order_id', "=", $order->id)
      ->where('status', "=", 1)
      ->select([
        DB::raw("COUNT(*) as total")
      ])
      ->get();

    $log_num_status_1 = intval($result[0]->total);
    $real_amount = intval($order->real_amount);
    $order_amount = intval($order->order_amount);

    $seeding_num =  $log_num_status_1 - ($real_amount - $order_amount);
    if ($seeding_num < 0) {
      $real_amount = $order_amount;
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where('id', "=", $order->id)
        ->update([
          'real_amount' => $order_amount,
          'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }

    $services = DB::table(TABLE_PREFIX . TABLE_SERVICES)
      ->where('idname', "=", $orders[0]->seeding_type)
      ->select(["id", "warranty", "max_hold", "idname"])
      ->get();

    if (count($services) == 0) {
      $this->resp->msg = "Invalid Service!";
      $this->jsonecho();
    }

    $service = $services[0];

    if ($log_num_status_1 >= $real_amount) {
      $date_warranty = new \Moment\Moment('now', date_default_timezone_get());
      $date_warranty->addDays(intval($service->warranty));

      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where('id', "=", $order->id)
        ->update([
          'status' => 'COMPLETED',
          'expired_warranty_at' => $date_warranty->format("Y-m-d H:i:s"),
          'updated_at' => date("Y-m-d H:i:s"),
        ]);
      $this->resp->msg = 'Order chuyển trạng thái kiểm tra đơn hoàn tất!';
      $this->jsonecho();
    }

    $resultInDay = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
      ->where('order_id', "=", $order->id)
      ->where('status', "=", 1)
      ->whereBetween("created_at", $day_start->format("Y-m-d H:i:s"), $day_end->format("Y-m-d H:i:s"))
      ->select([
        DB::raw("COUNT(*) as total")
      ])
      ->get();
    $log_day_num = intval($resultInDay[0]->total);

    if ($log_day_num >= intval($service->max_hold)) {
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where('id', "=", $order->id)
        ->update([
          'status' => 'HOLDING',
          'updated_at' => date("Y-m-d H:i:s"),
        ]);
      $this->resp->msg = 'Order chuyển trạng thái holding!';
      $this->jsonecho();
    }

    $result = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
      ->where('order_id', "=", $order->id)
      ->where('status', "=", 0)
      ->select([
        DB::raw("COUNT(*) as total")
      ])
      ->get();

    $log_num_status_1 = intval($result[0]->total);
    if ($log_num_status_1 >= intval(get_option("MAX_PAUSE"))) {
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where('id', "=", $order->id)
        ->update([
          'status' => 'PAUSED',
          'updated_at' => date("Y-m-d H:i:s"),
        ]);
      $this->resp->msg = 'Order chuyển trạng thái paused!';
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->msg = 'Check order success!';
    $this->jsonecho();
  }

  private function checkReportOrder()
  {
    $this->resp->result = false;

    $required_fields = ["account_uid", "seeding_uid", "seeding_type"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    try {
      $check = DB::table(TABLE_PREFIX . TABLE_ORDER_LOGS)
        ->where('seeding_uid', "=", Input::get("seeding_uid"))
        ->where('seeding_type', "=", Input::get("seeding_type"))
        ->where('account_uid', "=", Input::get("account_uid"))
        ->select([
          DB::raw("COUNT(*) as total")
        ])
        ->get();

      if ($check[0]->total == 1) {
        $this->resp->msg = 'report đã tồn tại';
        $this->jsonecho();
      }
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->msg = 'report không tồn tại';
    $this->jsonecho();
  }

  private function getCommentBuffOrder()
  {
    $this->resp->result = false;

    $pdo = DB::pdo();
    $pdo->beginTransaction();

    try {
      $required_fields = ["order_id"];
      foreach ($required_fields as $field) {
        if (!Input::get($field)) {
          $this->resp->msg = "Thiếu $field.";
          $this->jsonecho();
        }
      }

      $orders = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where('id', "=", Input::get("order_id"))
        ->select(["id", "status", "seeding_type"])
        ->get();

      if (count($orders) == 0) {
        $this->resp->msg = "Invalid Order!";
        $this->jsonecho();
      }

      if ($orders[0]->status == "COMPLETED") {
        $this->resp->msg = "Order đã hoàn thành!";
        $this->jsonecho();
      }

      $query = DB::table(TABLE_PREFIX . TABLE_ORDER_COMMENTS)
        ->where('order_id', '=', $orders[0]->id)
        ->where('status', '=', 0)
        ->orderBy(DB::raw('RAND()'));

      $comments = DB::query($query->getQuery()->getRawSql() . " FOR UPDATE")
        ->limit(1)
        ->get();

      if (count($comments) == 0) {

        $services = DB::table(TABLE_PREFIX . TABLE_SERVICES)
          ->where('idname', "=", $orders[0]->seeding_type)
          ->select(["id", "warranty"])
          ->get();

        if (count($services) == 0) {
          $this->resp->msg = "Invalid Service!";
          $this->jsonecho();
        }

        $date_warranty = new \Moment\Moment('now', date_default_timezone_get());
        $date_warranty->addDays(intval($services[0]->warranty));

        DB::table(TABLE_PREFIX . TABLE_ORDERS)
          ->where('id', "=", $orders[0]->id)
          ->update(array(
            'status' => 'COMPLETED',
            'expired_warranty_at' => $date_warranty->format("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
          ));
        $pdo->commit();
        $this->resp->msg = "Empty Comment!";
        $this->jsonecho();
      }

      DB::table(TABLE_PREFIX . TABLE_ORDER_COMMENTS)
        ->where('id', '=', $comments[0]->id)
        ->update(array("status" => 1));

      $pdo->commit();
    } catch (\Exception $ex) {
      $pdo->rollback();
      $this->resp->msg = "Lỗi hệ thống! Hãy thử lại. ";
      $this->resp->error = $ex->getMessage();
      $this->jsonecho();
    }


    $this->resp->result = true;
    $this->resp->comment = $comments[0]->comment;
    $this->resp->id = $comments[0]->id;
    $this->jsonecho();
  }

  private function resetAllCommentBuffOrder()
  {
    $this->resp->result = false;
    try {

      $required_fields = ["order_id"];
      foreach ($required_fields as $field) {
        if (!Input::get($field)) {
          $this->resp->msg = "Thiếu $field.";
          $this->jsonecho();
        }
      }

      $orders = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where('id', "=", Input::get("order_id"))
        ->select(["id"])
        ->get();

      if (count($orders) == 0) {
        $this->resp->msg = "Invalid Order!";
        $this->jsonecho();
      }

      DB::table(TABLE_PREFIX . TABLE_ORDER_COMMENTS)
        ->where('order_id', '=', $orders[0]->id)
        ->update(array(
          'status' => 0
        ));
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->msg = "reset all comment thành công";
    $this->jsonecho();
  }

  private function resetCommentBuffOrder()
  {
    $this->resp->result = false;
    try {

      $required_fields = ["comment_id"];
      foreach ($required_fields as $field) {
        if (!Input::get($field)) {
          $this->resp->msg = "Thiếu $field.";
          $this->jsonecho();
        }
      }

      DB::table(TABLE_PREFIX . TABLE_ORDER_COMMENTS)
        ->where('id', '=', Input::get("comment_id"))
        ->update(array(
          'status' => 0
        ));
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->msg = "reset comment thành công";
    $this->jsonecho();
  }

  private function addRedis($user_id, $seeding_type)
  {
    $redis = new Redis();
    $redis->connect(REDIS_HOST, REDIS_PORT);
    if (REDIS_PASS != '') {
      $redis->auth(REDIS_PASS);
    }

    $date = new \Moment\Moment('now', 'Asia/Ho_Chi_Minh');

    // xử lý danh sách user đang có
    $userList = [];
    if ($redis->exists(REDIS_USER_LIST)) {
      $userList = unserialize($redis->get(REDIS_USER_LIST));
    }
    if (!in_array($user_id, $userList)) {
      $userList[] = $user_id;
    }
    $redis->set(REDIS_USER_LIST, serialize($userList));


    // lấy thông tin data theo ngày và user_id
    $key_user_day = REDIS_USER_ID . "_" . $user_id . "_" . $date->format('Y-m-d');
    if ($redis->exists($key_user_day)) {
      $data_day = unserialize($redis->get($key_user_day));
    } else {
      $data_day = new stdClass;
    }

    if (empty($data_day->$seeding_type)) {
      $data_day->$seeding_type = 0;
    }
    $data_day->$seeding_type++;
    $redis->set($key_user_day, serialize($data_day));

    // Lấy thông tin data theo phút và user_id
    $key_user_minute = REDIS_USER_ID . "_" . $user_id . "_minute";
    if ($redis->exists($key_user_minute)) {
      $data_minute = unserialize($redis->get($key_user_minute));
    } else {
      $data_minute = new stdClass;
    }

    if (empty($data_minute->$seeding_type)) {
      $data_minute->$seeding_type = new stdClass;
      $data_minute->$seeding_type->key = $date->format('Y-m-d H:i');
      $data_minute->$seeding_type->value = 0;
    }

    if ($data_minute->$seeding_type->key == $date->format('Y-m-d H:i')) {
      $data_minute->$seeding_type->value++;
    } else {
      $data_minute->$seeding_type->key = $date->format('Y-m-d H:i');
      $data_minute->$seeding_type->value = 0;
    }
    $redis->set($key_user_minute, serialize($data_minute));
  }

  private function listBuffOrderbyStatus($status)
  {
    $this->resp->result = false;

    $required_fields = ["limit"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    try {
      $result = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->whereIn("status", $status)
        ->orderBy(DB::raw("RAND()"))
        ->select([
          "id", "seeding_type", "seeding_uid", "uid_post", "wwwURL", "share_type", "order_amount", "reaction_type", "created_at", "status"
        ])
        ->limit(Input::get("limit"))
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->data = array_map(function ($item) {
      $item->feedbackTargetID = base64_encode("feedback:" . $item->seeding_uid);
      return $item;
    }, $result);
    $this->jsonecho();
  }

  private function updateBuffOrderPending()
  {
    $this->resp->result = false;

    $required_fields = ["order_id"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }
    $dataUpdate = [];
    $dataUpdate['updated_at'] = date("Y-m-d H:i:s");
    if (Input::get("wwwURL")) {
      $dataUpdate["wwwURL"] = urldecode(Input::get("wwwURL"));
    }

    if (Input::get("seeding_uid")) {
      $dataUpdate["seeding_uid"] = urldecode(Input::get("seeding_uid"));
    }

    if (Input::get("start_num")) {
      $dataUpdate["start_num"] = Input::get("start_num");
    }

    if (Input::get("uid_post")) {
      $dataUpdate["uid_post"] = Input::get("uid_post");
    }

    if (Input::get("__typename")) {
      $dataUpdate["__typename"] = Input::get("__typename");
    }

    if (Input::get("share_type")) {
      $dataUpdate["share_type"] = Input::get("share_type");
    }

    if (Input::get("note_extra")) {
      $dataUpdate["note_extra"] = Input::get("note_extra");
    }

    if (Input::get("status")) {
      $dataUpdate["status"] = Input::get("status");
    } else {
      $dataUpdate["status"] = "RUNNING";
    }

    try {
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where("id", "=", Input::get("order_id"))
        ->update($dataUpdate);
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->msg = "Cập nhật thành công";
    $this->jsonecho();
  }

  private function updateBuffOrderWarranty()
  {
    $this->resp->result = false;

    $required_fields = ["order_id", "seeding_num"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }
    $order_id = Input::get("order_id");
    // $user_id = Input::get("user_id");
    $user_id = 3;

    try {
      $query = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where(TABLE_PREFIX . TABLE_ORDERS . ".id", "=", $order_id)
        ->select([
          TABLE_PREFIX . TABLE_ORDERS . ".id",
          TABLE_PREFIX . TABLE_ORDERS . ".status",
          TABLE_PREFIX . TABLE_ORDERS . ".expired_warranty_at",
          TABLE_PREFIX . TABLE_ORDERS . ".start_num",
          TABLE_PREFIX . TABLE_ORDERS . ".order_amount",
          TABLE_PREFIX . TABLE_ORDERS . ".seeding_type",
        ]);
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
    if ($order->status != "CHECKING_WARRANTY") {
      $this->resp->msg = "Không thể bảo hành khi đơn chưa lên trạng thái kiểm tra bảo hành";
      $this->jsonecho();
    }

    $seeding_num = intval(Input::get("seeding_num"));
    $total_order = $order->start_num + $order->order_amount;
    if ($seeding_num > 0 && $seeding_num < $total_order) {
      $up_amount = $total_order - $seeding_num;
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where("id", "=", $order->id)
        ->update([
          "real_amount" => DB::raw("real_amount + $up_amount"),
          "status" => "RUNNING",
          "updated_at" => date("Y-m-d H:i:s")
        ]);

      DB::table(TABLE_PREFIX . TABLE_WARRANTY_LOGS)
        ->insert([
          "user_id" => $user_id,
          "order_id" => $order->id,
          "seeding_type" => $order->seeding_type,
          "total" => $up_amount,
          "date_at" => date("Y-m-d H:i:s")
        ]);
    } else {
      DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where("id", "=", $order->id)
        ->update([
          "status" => "COMPLETED",
          "updated_at" => date("Y-m-d H:i:s")
        ]);
      $this->resp->msg = "Đơn này không cần bảo hành";
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->msg = "Bảo hành thành công";
    $this->jsonecho();
  }

  private function countPendingOrder()
  {
    $this->resp->result = false;

    try {
      $result = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where("status", "=", 'PENDING')
        ->select(DB::raw("COUNT(*) as total"))
        ->get();
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->total = $result[0]->total;
    $this->resp->msg = "Lấy thành công";
    $this->jsonecho();
  }
}
