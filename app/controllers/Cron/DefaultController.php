<?php

namespace Cron;

use Controller;
use Input;
use DateTime;
use Exception;
use DB;
use stdClass;
use Redis;

/**
 * Default Controller
 */
class DefaultController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    set_time_limit(0);
    $this->resetHold();
    $this->resetHold2();
  }

  /**
   * Có nhiệm vụ nếu đơn runnning < 5 thì sẽ reset lại hold => running, và đồng thời tăng x2 max_hold
   */
  private function resetHold()
  {
    $date = new \Moment\Moment('now', 'Asia/Ho_Chi_Minh');
    $date = $date->subtractDays(1);

    $day_start = $date->cloning()->startOf('day');
    $day_end = $date->cloning()->endOf('day');

    DB::table(TABLE_PREFIX . TABLE_ORDERS)
      ->where("status", "=", "HOLDING")
      ->whereBetween("updated_at", $day_start->format("Y-m-d H:i:s"), $day_end->format("Y-m-d H:i:s"))
      ->update([
        'status' => 'RUNNING',
        'updated_at' => date("Y-m-d H:i:s")
      ]);
  }

  private function resetHold2()
  {
    $services = DB::table(TABLE_PREFIX . TABLE_SERVICES)
      // ->where('is_maintaince', "=", "0")
      // ->where('is_public', "=", "1")
      ->get();

    foreach ($services as $service) {
      // count order of services running in one type
      $ordersRunning = DB::table(TABLE_PREFIX . TABLE_ORDERS)
        ->where("seeding_type", "=", $service->idname)
        ->where('status', "=", "RUNNING")
        ->select([
          DB::raw("COUNT(*) as total")
        ])
        ->get();

      if (intval($ordersRunning[0]->total) <= intval(get_option("MAX_ORDER_RUNNING"))) {

        DB::table(TABLE_PREFIX . TABLE_SERVICES)
          ->where('id', "=", $service->id)
          ->update([
            'max_hold' => DB::raw("max_hold + " . get_option("MAX_HOLD")),
          ]);

        DB::table(TABLE_PREFIX . TABLE_ORDERS)
          ->where('seeding_type', "=", $service->idname)
          ->where('status', "=", "HOLDING")
          ->update([
            'status' => 'RUNNING',
            'updated_at' => date("Y-m-d H:i:s"),
          ]);
      }
    }
  }
}
