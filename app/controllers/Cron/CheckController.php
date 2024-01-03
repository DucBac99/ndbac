<?php

namespace Cron;

use Controller;
use Input;
use DateTime;
use Exception;
use DB;
use stdClass;
use GraphQL;
use ProxyStatic\ProxyFB;
use Redis;

/**
 * Check Controller
 */
class CheckController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    set_time_limit(0);
    $this->checkTuongTac();
  }

  /**
   * Có nhiệm vụ nếu đơn runnning < 5 thì sẽ reset lại hold => running, và đồng thời tăng x2 max_hold
   */
  private function testCron()
  {
    $user_id = 1;
    $seeding_type = 'buff-likepost';
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

  private function checkTuongTac()
  {
    $redis = new Redis();
    $redis->connect(REDIS_HOST, REDIS_PORT);
    if (REDIS_PASS != '') {
      $redis->auth(REDIS_PASS);
    }

    // xử lý danh sách user đang có
    $userList = [];
    if ($redis->exists(REDIS_USER_LIST)) {
      $userList = unserialize($redis->get(REDIS_USER_LIST));
    }
    if (count($userList) == 0) {
      return;
    }

    $date = new \Moment\Moment('now', 'Asia/Ho_Chi_Minh');
    $date_day = $date->subtractDays(1)->startOf('day')->format('Y-m-d');

    // $key_user_day = REDIS_USER_ID."_3_" . $date_day;
    // $data_day = null;
    // if ($redis->exists($key_user_day)) {
    //   $data_day = unserialize($redis->get($key_user_day));
    // }

    // print_r($data_day);
    // return;
    foreach ($userList as $user) {
      $key_user_day = REDIS_USER_ID . "_" . $user . "_" . $date_day;
      $data_day = null;
      if ($redis->exists($key_user_day)) {
        $data_day = unserialize($redis->get($key_user_day));
      }

      if ($data_day == null) {
        continue;
      }
      $this->checkAndAdd($redis, $user, $data_day, $date_day);
    }
  }

  private function checkAndAdd($redis, $user_id, $data_day, $date_day)
  {
    foreach ($data_day as $seeding_type => $value) {
      try {
        $check = DB::table(TABLE_PREFIX . TABLE_INTERACT_LOGS)
          ->where('user_id', "=", $user_id)
          ->where('seeding_type', "=", $seeding_type)
          ->where('date_at', "=", $date_day)
          ->select([
            DB::raw("COUNT(id) as total")
          ])
          ->get();

        if ($check[0]->total == 1) {
          continue;
        }

        DB::table(TABLE_PREFIX . TABLE_INTERACT_LOGS)
          ->insert(array(
            'user_id' => $user_id,
            'seeding_type' => $seeding_type,
            'total' => $value,
            'date_at' => $date_day,
          ));
        $key_user_day = REDIS_USER_ID . "_" . $user_id . "_" . $date_day;
        $redis->delete($key_user_day);
      } catch (\Exception $ex) {
      }
    }
  }
}
