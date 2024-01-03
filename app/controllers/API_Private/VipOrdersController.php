<?php

namespace API_Private;

use Controller;
use Input;
use DB;
use Redis;
use stdClass;

/**
 * VipOrders Controller
 */
class VipOrdersController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $token = Input::get("access_token");
    if (!$token || $token != TOKEN_VIP_ORDERS) {
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
      case 'list-vip-order':
        $this->listVipOrder();
        break;
      case 'add-buff-order':
        $this->addBuffOrder();
        break;
      case 'reset-vip-order':
        $this->resetVipOrder();
        break;
      default:
        # code...
        break;
    }
  }
  private function listVipOrder()
  {
    $this->resp->result = false;

    $required_fields = ["limit", "type_orders"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    $used_at = Input::get("used_at");
    if (!$used_at) {
      $used_at = 10 * 60;
    }

    $type_orders = explode("|", Input::get("type_orders"));
    $sql = "";
    $length_type_orders = count($type_orders);
    $each_limit = intval(Input::get("limit"));

    for ($i = 0; $i < $length_type_orders; $i++) {
      $seeding_type = $type_orders[$i];
      if ($seeding_type != '') {
        $sql = $sql . $this->genSqlOrders($seeding_type, $each_limit);
        if ($i < $length_type_orders - 1) {
          $sql = $sql . "
                      UNION DISTINCT
                  ";
        }
      }
    }

    $result = DB::query($sql)->get();
    $data = [];
    $ids = [];
    foreach ($result as $row) {
      $datas['id']            = $row->id;
      $ids[] = $row->id;
      $datas['seeding_uid']   = $row->seeding_uid;
      if (in_array($row->seeding_type, ["vip-like", "vip-comment", "vip-share"])) {
        $datas['uid_post']  = '';
        $datas['wwwURL']  = $row->wwwURL;
      }
      $datas['seeding_type'] = $row->seeding_type;
      $datas['order_amount'] = $row->order_amount;
      $datas['share_type'] = 0;
      $datas['reaction_type'] = $row->reaction_type;
      $datas['comment_need'] = '';
      $datas['created_at'] = $row->created_at;
      $data[] = $datas;
    }

    DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)->update(array(
      'used_at' => time() + $used_at,
      'updated_at' => date('Y-m-d H:i:s')
    ));

    $this->resp = $data;
    $this->jsonecho();
  }

  private function genSqlOrders($seeding_type, $limit)
  {

    $sort = "ORDER BY RAND()";
    return "
                (
                    SELECT
                        od.*
                    FROM
                        " . TABLE_PREFIX . TABLE_VIP_ORDERS . " od
                    WHERE
                        seeding_type = '$seeding_type'
                        AND status = 'RUNNING'
                        AND used_at < UNIX_TIMESTAMP(NOW())
                    $sort
                    LIMIT $limit
                )";
  }

  private function addBuffOrder()
  {
    $this->resp->result = false;

    $required_fields = ["seeding_uid", "wwwURL", "share_type", "start_num", "order_amount", "seeding_type", "status", "__typename", "order_id"];
    foreach ($required_fields as $field) {
      if (!Input::get($field)) {
        $this->resp->msg = "Thiếu $field.";
        $this->jsonecho();
      }
    }

    if (Input::post("reaction_type")) {
      $reaction_type = Input::post("reaction_type");
    } else {
      $reaction_type = 'LIKE';
    }

    try {
      $checkVipOrder = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
        ->where('id', '=', Input::get("order_id"))
        ->select(["id", "comment_need"])
        ->get();

      if (count($checkVipOrder) == 0) {
        $this->resp->msg = "VIPOrder ID không tồn tại";
        $this->jsonecho();
      }

      $check = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where('is_vip', '=', 1)
        ->where('user_id', '=', 3)
        ->where('source_id', '=', $checkVipOrder[0]->id)
        ->where('seeding_uid', '=', Input::get("seeding_uid"))
        ->select(["id"])
        ->get();

      if (count($check) > 0) {
        $this->resp->msg = "Seeding UID của đơn vip này đã thêm rồi";
        $this->jsonecho();
      }

      $order_id = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->insert(array(
          "user_id" => 3,
          "source_from" => 1,
          "source_id" => Input::get("order_id"),
          "seeding_uid" => Input::get("seeding_uid"),
          "uid_post" => "",
          "order_amount" => Input::get("order_amount"),
          "real_amount" => Input::get("order_amount"),
          "start_num" => Input::get("start_num"),
          "seeding_type" => Input::get("seeding_type"),
          "reaction_type" => $reaction_type,
          "comment_need" => '',
          "status" => Input::get("status"),
          "__typename" => Input::get("__typename"),
          "wwwURL" => urldecode(Input::get("wwwURL")),
          "completed_at" => 0,
          "price" => 0,
          "seeding_num" => 0,
          "priceAdmin" => 0,
          "used_at" => 0,
          "note" => '',
          "note_extra" => '',
          "group_id" => '',
          "share_type" => Input::get("share_type"),
          "is_vip" => 1,
          "created_at" => date("Y-m-d H:i:s"),
          "updated_at" => date("Y-m-d H:i:s"),
          "expired_warranty_at" => date("Y-m-d H:i:s"),
        ));

      if (Input::get("seeding_type") == "buff-comment") {
        $comment_need = preg_split("/\\r\\n|\\r|\\n/", $checkVipOrder[0]->comment_need);
        DB::table(TABLE_PREFIX . TABLE_ORDER_COMMENTS)
          ->insert(array_map(function ($item) use ($order_id) {
            return array(
              'order_id' => $order_id,
              'comment' => $item,
              'status' => 0,
              'expired_at' => date("Y-m-d H:i:s")
            );
          }, $comment_need));
      }
    } catch (\Exception $ex) {
      $this->resp->msg = $ex->getMessage();
      $this->jsonecho();
    }

    $this->resp->result = true;
    $this->resp->order_id = $order_id;
    $this->resp->msg = "Thêm thành công";
    $this->jsonecho();
  }

  private function resetVipOrder()
  {
    $this->resp->result = false;

    DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)->update(array(
      'used_at' => 0,
      'updated_at' => date('Y-m-d H:i:s')
    ));

    $this->resp->result = true;
    $this->jsonecho();
  }
}
