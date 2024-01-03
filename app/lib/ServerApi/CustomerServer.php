<?php

namespace ServerApi;

use stdClass;

class CustomerServer
{
  private $api_url;
  private $api_key;
  private $api_user_id;

  public function __construct($api_url, $api_key, $api_user_id)
  {
    $this->api_url = $api_url;
    $this->api_key = $api_key;
    $this->api_user_id = $api_user_id;
  }

  private function post($url, $body, $headers = [])
  {
    $client = new \GuzzleHttp\Client($headers);
    $resp = $client->request('POST', $url, [
      'verify' => false,
      'form_params' => $body
    ]);
    $resp = @json_decode($resp->getBody());
    return $resp;
  }

  private function get($url, $params, $headers = [])
  {
    $client = new \GuzzleHttp\Client($headers);
    $resp = $client->request('GET', $url, [
      'verify' => false,
      'query' => $params
    ]);
    $resp = @json_decode($resp->getBody());
    return $resp;
  }

  public function orderBuff($seeding_uid, $seeding_type, $order_amount, $reaction_type = 'LIKE', $comment_need = '', $note = 'purchased at api web sabommo')
  {
    $output = new stdClass;
    $output->result = false;

    try {
      $params = [
        'seeding_uid' => $seeding_uid,
        'seeding_type' => $seeding_type,
        'order_amount' => $order_amount,
        'reaction_type' => $reaction_type,
        'comment_need' => $comment_need,

        'action' => 'buy-orders-buff-api',
        'id_user' => $this->api_user_id,
        'token' => $this->api_key,
        'note' => $note
      ];
      $resp = $this->get($this->api_url, $params);

      if (!is_object($resp)) {
        $output->msg = 'Lỗi hệ thống, Hãy thử lại';
        return $output;
      } else if ($resp->result == false) {
        $output->msg = $resp->msg;
        return $output;
      }

      $output->order_id = $resp->id;
      $output->start_num = $resp->start_num;
    } catch (\GuzzleHttp\Exception\ClientException $ex) {
      $output->msg = $ex->getMessage();
      return $output;
    }

    $output->result = true;
    return $output;
  }

  public function orderVip($seeding_uid, $seeding_type, $order_amount, $month, $reaction_type = 'LIKE', $comment_need = '', $note = 'purchased at api web sabommo')
  {
    $output = new stdClass;
    $output->result = false;

    try {
      $params = [
        'seeding_uid' => $seeding_uid,
        'seeding_type' => $seeding_type,
        'order_amount' => $order_amount,
        'reaction_type' => $reaction_type,
        'comment_need' => $comment_need,

        'month_count' => $month,
        'post_count' => 10,

        'action' => 'buy-orders-vip-api',
        'id_user' => $this->api_user_id,
        'token' => $this->api_key,
        'note' => $note
      ];
      $resp = $this->get($this->api_url, $params);

      if (!is_object($resp)) {
        $output->msg = 'Lỗi hệ thống, Hãy thử lại';
        return $output;
      } else if ($resp->result == false) {
        $output->msg = $resp->msg;
        return $output;
      }

      $output->order_id = $resp->id;
    } catch (\GuzzleHttp\Exception\ClientException $ex) {
      $output->msg = $ex->getMessage();
      return $output;
    }

    $output->result = true;
    return $output;
  }

  public static function get_start_num($seeding_uid, $seeding_type)
  {
    $output = new stdClass;
    $output->result = false;

    try {
      $params = [
        'seeding_uid' => $seeding_uid,
        'seeding_type' => $seeding_type,
        'action' => 'get-start-num',
      ];

      $client = new \GuzzleHttp\Client([]);
      $resp = $client->request('GET', 'https://customer.sabommo.net/api/index.php', [
        'verify' => false,
        'query' => $params
      ]);
      $resp = $resp->getBody();

      if (!is_numeric($resp)) {
        $output->msg = 'Không lấy được dữ liệu ban đầu';
        return $output;
      }
      $output->start_num = intval($resp);
    } catch (\GuzzleHttp\Exception\ClientException $ex) {
      $output->msg = $ex->getMessage();
      return $output;
    }

    $output->result = true;
    return $output;
  }
}
