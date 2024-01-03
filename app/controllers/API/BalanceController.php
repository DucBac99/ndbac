<?php

namespace API;

use Controller;
use Input;
use DB;
use Exception;

/**
 * Balance Controller
 */
class BalanceController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");
    // Auth
    if (!$AuthUser) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $this->balance();
  }

  public function balance()
  {
    $this->resp->result = 0;
    $AuthUser = $this->getVariable("AuthUser");

    $this->resp->result = 1;
    $this->resp->balance = (int)$AuthUser->get("balance");
    $this->resp->total = (int)$AuthUser->get("total");
    $this->jsonecho();
  }
}
