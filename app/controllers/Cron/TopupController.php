<?php

namespace Cron;

use Controller;
use Input;
use DateTime;
use Exception;
use DB;
use stdClass;
use OneAPI;

/**
 * Topup Controller
 */
class TopupController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    set_time_limit(0);

    $this->fetchBanking();

    echo "Cron task processed!";
  }

  /**
   * Process fetch Data Banking
   */
  private function fetchBanking()
  {
    $AuthSite = $this->getVariable('AuthSite');

    $Status = (bool)$AuthSite->get("banking.auth.status");

    $username = $AuthSite->get("banking.auth.username");
    $password = $AuthSite->get("banking.auth.password") ? \Defuse\Crypto\Crypto::decrypt($AuthSite->get("banking.auth.password"), \Defuse\Crypto\Key::loadFromAsciiSafeString(CRYPTO_KEY)) : "";
    $bank_code = $AuthSite->get("banking.auth.bank_code");

    $AccountNo = $AuthSite->get("banking.info.account_number");
    if (!$username || !$password || !$AccountNo || !$bank_code) {
      return false;
    }

    $apiKey = "2.910208f8a82801f5220faa25ffa63e6d";

    $oneAPI = new OneAPI($bank_code, $apiKey);
    try {
      $result_login = $oneAPI->login($username, $password);
      // print_r($result_login);
      if (empty($result_login->token->accessToken)) throw new Exception("No find token");

      $result_transactions = $oneAPI->getTransactions($AccountNo, $result_login->token->accessToken);
      print_r($result_transactions);
      if (empty($result_transactions->result)) throw new Exception("No find data");

      $result = $result_transactions->data;
    } catch (\Exception $ex) {
      print_r($ex->getMessage());
      return false;
    }

    if ($bank_code == "VCB") {
      if (isset($result->code) && $result->code == "00") {
        foreach ($result->transactions as $item) {
          if ($item->CD == "-" || strpos($item->Reference, ' - ') === false) continue;
          $Payment = Controller::model("Payment", $item->Reference);
          if ($Payment->isAvailable()) continue;

          $desc = strtolower($item->Description);
          $total = intval(str_replace(",", "", $item->Amount));

          $re = strtolower($AuthSite->get("banking.info.content")) . "( |)(.*?)($|.ct|-| )";
          preg_match_all('/' . $re . '/m', $desc, $matches, PREG_SET_ORDER, 0);

          if (isset($matches[0]) && count($matches[0]) > 2) {
            echo $matches[0][2] . "----";
            $User = Controller::model("User", intval($matches[0][2]));
            if (!$User->isAvailable()) {
              continue;
            }
            $Payment->set("user_id", $User->get("id"))
              ->set("data", $item->Description)
              ->set("status", "paid")
              ->set("payment_gateway", $bank_code)
              ->set("payment_id", $item->Reference)
              ->set("total", $total)
              ->set("site_id", $AuthSite->get("id"))
              ->set("currency", "VND")
              ->save();

            $before = $User->get("balance");
            $User->set("total_deposit", (int)$User->get("total_deposit") + $total)
              ->set("balance", (int)$User->get("balance") + $total)
              ->save();

            DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
              ->insert(array(
                "user_id" => $User->get("id"),
                "before" => $before,
                "money" => $total,
                "type" => "+",
                "site_id" => $AuthSite->get("id"),
                "after" => $before + $total,
                "seeding_type" => "ADD_BALANCE_VCB",
                "seeding_uid" => $User->get("id"),
                "type_code" => "ADD_BALANCE_VCB",
                "content" => "Nạp tiền thành công REF#" . $item->Reference,
                'date' => date("Y-m-d H:i:s")
              ));
          }
        }
      }
    } else if ($bank_code == "MB") {
      if (isset($result->result->responseCode) && $result->result->responseCode == "00") {
        foreach ($result->transactionHistoryList as $item) {
          $Payment = Controller::model("Payment", $item->refNo);
          if ($Payment->isAvailable()) continue;
          $desc = strtolower($item->description);
          $total = intval($item->creditAmount);

          $re = strtolower($AuthSite->get("banking.info.content")) . "( |)(.*?)($|.ct|-| )";
          preg_match_all('/' . $re . '/m', $desc, $matches, PREG_SET_ORDER, 0);
          if (isset($matches[0]) && count($matches[0]) > 2) {
            echo $matches[0][2] . "----";
            $User = Controller::model("User", intval($matches[0][2]));
            if (!$User->isAvailable()) {
              continue;
            }
            $Payment->set("user_id", $User->get("id"))
              ->set("data", $item->description)
              ->set("status", "paid")
              ->set("payment_gateway", $bank_code)
              ->set("payment_id", $item->refNo)
              ->set("total", $total)
              ->set("site_id", $AuthSite->get("id"))
              ->set("currency", "VND")
              ->save();

            $before = $User->get("balance");
            $User->set("total_deposit", (int)$User->get("total_deposit") + $total)
              ->set("balance", (int)$User->get("balance") + $total)
              ->save();

            DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
              ->insert(array(
                "user_id" => $User->get("id"),
                "before" => $before,
                "money" => $total,
                "type" => "+",
                "site_id" => $AuthSite->get("id"),
                "after" => $before + $total,
                "seeding_type" => "ADD_BALANCE_VCB",
                "seeding_uid" => $User->get("id"),
                "type_code" => "ADD_BALANCE_VCB",
                "content" => "Nạp tiền thành công REF#" . $item->refNo,
                'date' => date("Y-m-d H:i:s")
              ));
          }
        }
      }
    } else if ($bank_code == "ACB") {
      if (isset($result->result->responseCode) && $result->result->responseCode == "00") {
        foreach ($result->transactionHistoryList as $item) {
          $reference = explode(" ", explode(" GD ", $item->description)[1])[0];
          if ($item->type == "OUT" || strlen($reference) < 12) continue;
          $Payment = Controller::model("Payment", $item->refNo);
          if ($Payment->isAvailable()) continue;
          $desc = strtolower($item->description);
          $total = intval($item->amount);

          $re = strtolower($AuthSite->get("banking.info.content")) . "( |)(.*?)($|.ct|-| )";
          preg_match_all('/' . $re . '/m', $desc, $matches, PREG_SET_ORDER, 0);

          if (isset($matches[0]) && count($matches[0]) > 2) {
            echo $matches[0][2] . "----";
            $User = Controller::model("User", intval($matches[0][2]));
            if (!$User->isAvailable()) {
              continue;
            }
            $Payment->set("user_id", $User->get("id"))
              ->set("data", $item->description)
              ->set("status", "paid")
              ->set("payment_gateway", $bank_code)
              ->set("payment_id", $reference)
              ->set("total", $total)
              ->set("site_id", $AuthSite->get("id"))
              ->set("currency", "VND")
              ->save();

            $before = $User->get("balance");
            $User->set("total_deposit", (int)$User->get("total_deposit") + $total)
              ->set("balance", (int)$User->get("balance") + $total)
              ->save();

            DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
              ->insert(array(
                "user_id" => $User->get("id"),
                "before" => $before,
                "money" => $total,
                "type" => "+",
                "site_id" => $AuthSite->get("id"),
                "after" => $before + $total,
                "seeding_type" => "ADD_BALANCE_VCB",
                "seeding_uid" => $User->get("id"),
                "type_code" => "ADD_BALANCE_VCB",
                "content" => "Nạp tiền thành công REF#" . $reference,
                'date' => date("Y-m-d H:i:s")
              ));
          }
        }
      }
    } else if ($bank_code == "TCBB") {
      if (!is_array($result)) {
        return false;
      }

      foreach ($result as $item) {
        $reference = $item->reference;
        $Payment = Controller::model("Payment", $item->refNo);
        if ($Payment->isAvailable()) continue;
        $desc = strtolower($item->description);
        $total = intval($item->transactionAmountCurrency->amount);

        $re = strtolower($AuthSite->get("banking.info.content")) . "( |)(.*?)($|.ct|-| )";
        preg_match_all('/' . $re . '/m', $desc, $matches, PREG_SET_ORDER, 0);

        if (isset($matches[0]) && count($matches[0]) > 2) {
          echo $matches[0][2] . "----";
          $User = Controller::model("User", intval($matches[0][2]));
          if (!$User->isAvailable()) {
            continue;
          }
          $Payment->set("user_id", $User->get("id"))
            ->set("data", $item->description)
            ->set("status", "paid")
            ->set("payment_gateway", $bank_code)
            ->set("payment_id", $reference)
            ->set("total", $total)
            ->set("site_id", $AuthSite->get("id"))
            ->set("currency", "VND")
            ->save();

          $before = $User->get("balance");
          $User->set("total_deposit", (int)$User->get("total_deposit") + $total)
            ->set("balance", (int)$User->get("balance") + $total)
            ->save();

          DB::table(TABLE_PREFIX . TABLE_FLUCTUATIONS)
            ->insert(array(
              "user_id" => $User->get("id"),
              "before" => $before,
              "money" => $total,
              "type" => "+",
              "site_id" => $AuthSite->get("id"),
              "after" => $before + $total,
              "seeding_type" => "ADD_BALANCE_VCB",
              "seeding_uid" => $User->get("id"),
              "type_code" => "ADD_BALANCE_VCB",
              "content" => "Nạp tiền thành công REF#" . $reference,
              'date' => date("Y-m-d H:i:s")
            ));
        }
      }
    }

    return true;
  }
}
