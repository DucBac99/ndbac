<?php

namespace ServerApi;

use stdClass;

class TraoDoiSub
{
  private $apiKey;
  private $apiIdUrl = 'https://id.traodoisub.com/api.php';
  private $apiUrl = 'https://traodoisub.com/';
  private $baseUrl = 'https://traodoisub.com/';
  private $cookie = '';
  private $proxy = '';

  public function __construct($apiKey = null, $proxy = null)
  {
    if ($apiKey) $this->apiKey = $apiKey;
    if ($proxy) $this->proxy = $proxy;
  }

  public function checkUid($url)
  {
    $output = new stdClass;
    $output->result = false;

    try {

      $client = new \GuzzleHttp\Client([]);
      $resp = $client->request('POST', $this->apiIdUrl, [
        'form_params' => [
          'link' => $url,
        ]
      ]);
      $resp = @json_decode($resp->getBody());
      if (!is_object($resp)) {
        $output->msg = 'Lỗi hệ thống, Hãy thử lại';
        return $output;
      } else if (!empty($resp->error)) {
        $output->msg = $resp->error;
        return $output;
      }
    } catch (\GuzzleHttp\Exception\ClientException $ex) {
      $output->msg = $ex->getMessage();
      return $output;
    }

    $output->result = true;
    $output->id = $resp->id;
    $output->msg = 'Lấy thành công Facebook ID';
    return $output;
  }


  private function post($url, $body)
  {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => http_build_query($body),
      CURLOPT_HTTPHEADER => array(
        'authority: traodoisub.com',
        'accept: */*',
        'accept-language: en,vi-VN;q=0.9,vi;q=0.8,fr-FR;q=0.7,fr;q=0.6,en-US;q=0.5,hy;q=0.4',
        'content-type: application/x-www-form-urlencoded; charset=UTF-8',
        'cookie: ' . $this->cookie,
        'origin: https://traodoisub.com',
        'referer: https://traodoisub.com/',
        'sec-ch-ua: "Google Chrome";v="111", "Not(A:Brand";v="8", "Chromium";v="111"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "macOS"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36',
        'x-requested-with: XMLHttpRequest'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }

  public function login()
  {
    $arr = explode("|", $this->apiKey);
    $client = new \GuzzleHttp\Client();
    $resp = $client->request('POST', $this->baseUrl . "/scr/login.php", [
      'verify' => false,
      'multipart' => [
        [
          'name' => 'username',
          'contents' => $arr[0]
        ],
        [
          'name' => 'password',
          'contents' => $arr[1]
        ]
      ]
    ]);
    foreach ($resp->getHeaders() as $name => $values) {
      if ($name == "Set-Cookie") {
        $this->cookie = explode("; ", join(", ", $values))[0];
      }
    }
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

  public function orderBuff($seeding_uid, $seeding_type, $order_amount, $reaction_type = 'LIKE', $comment_need = [], $note = 'SABOMMO-')
  {
    $output = new stdClass;
    $output->result = false;

    try {
      $params = [
        'id' => $seeding_uid,
        'sl' => $order_amount,
        'is_album' => 'not',
        'speed' => 1,
        'dateTime' => date("Y-m-d H:i:s"),
        'maghinho' => $note,
        'noidung' => json_encode($comment_need),
        'loaicx' => $reaction_type
      ];

      $parts = explode('-', $seeding_type);
      $url = $parts[1];
      if ($seeding_type == "buff-likepost") {
        $url = "like";
      } else if ($seeding_type == "buff-likepage") {
        $url = "fanpage";
      } else if ($seeding_type == "buff-follow") {
        $url = "follow";
      } else if ($seeding_type == "buff-share") {
        $url = "share";
      } else if ($seeding_type == "buff-comment") {
        $url = "comment";
      }

      $resp = $this->post($this->apiUrl . "/mua/" . $url . "/themid.php", $params);
    } catch (\GuzzleHttp\Exception\ClientException $ex) {
      $output->msg = $ex->getMessage();
      return $output;
    }

    return $resp;
  }

  public function orderVip($seeding_uid, $seeding_type, $order_amount, $time_pack, $note = 'SABOMMO-')
  {
    $output = new stdClass;
    $output->result = false;

    try {
      $params = [
        'id' => $seeding_uid,
        'sever' => 1,
        'time_pack' => $time_pack,
        'dateTime' => date("Y-m-d H:i:s"),
        'maghinho' => $note,
        'packet' => $order_amount,
        'post' => 5
      ];

      $url = $seeding_type;
      if ($seeding_type == "vip-reaction") {
        $url = "viplike";
      }

      $resp = $this->post($this->apiUrl . "/mua/" . $url . "/themid.php", $params);
    } catch (\GuzzleHttp\Exception\ClientException $ex) {
      $output->msg = $ex->getMessage();
      return $output;
    }

    return $resp;
  }
}
