<?php

namespace API_Private;

use Controller;
use Input;
use DB;
use Redis;
use stdClass;

/**
 * GroupOrders Controller
 */
class GroupOrdersController extends Controller
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
      case 'list-group-order':
        $this->listGroupOrder();
        break;
      default:
        # code...
        break;
    }
  }

  private function listGroupOrder()
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
      if ($seeding_type == '') {
        continue;
      }
      $sql = $sql . $this->genSqlGroups($seeding_type, Input::get("account_uid"), $each_limit);
      if ($i < $length_type_orders - 1) {
        $sql = $sql . "
                      UNION DISTINCT
                  ";
      }
    }

    $result = DB::query($sql)->get();
    $sql = [];

    foreach ($result as $row) {
      $group_id = $row->group_id;
      $sql[] = $this->genSqlOrders($seeding_type, $group_id);
    }

    if (count($sql) == 0) {
      $this->resp = [];
      $this->jsonecho();
    }

    $result = DB::query(join("  UNION DISTINCT ", $sql))->get();

    $this->resp = $result;
    $this->jsonecho();
  }

  private function genSqlGroups($seeding_type, $account_uid, $limit)
  {
    $sort = "ORDER BY RAND()";
    return "
                (
                    SELECT DISTINCT od.group_id
                    FROM
                        " . TABLE_PREFIX . TABLE_ORDERS . " od
                    WHERE
                        od.group_id != ''
                        AND NOT EXISTS (
                            SELECT
                                *
                            FROM
                                " . TABLE_PREFIX . TABLE_ORDER_LOGS . " lg1
                            WHERE
                                lg1.account_uid = '$account_uid'
                                AND lg1.group_id = od.id
                                AND lg1.seeding_type = '$seeding_type'
                        )
                    $sort
                    LIMIT $limit
                )";
  }

  private function genSqlOrders($seeding_type, $group_id)
  {
    $sort = "ORDER BY RAND()";
    return " (
          SELECT
              id, group_id, seeding_uid, uid_post, wwwURL, seeding_type, order_amount, share_type, reaction_type, comment_need, created_at
          FROM
              " . TABLE_PREFIX . TABLE_ORDERS . "
          WHERE
              seeding_type = '$seeding_type'
              AND status = 'RUNNING'
              AND group_id = '$group_id'
          $sort
          LIMIT 1
      )";
  }
}
