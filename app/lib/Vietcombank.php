<?php

class Vietcombank
{
  private $JWT;
  private $AccountNo;
  private $username;
  private $password;
  private $cookie;

  public function __construct($JWT, $AccountNo, $username, $password, $cookie)
  {
    $this->JWT = $JWT;
    $this->AccountNo = $AccountNo;
    $this->username = $username;
    $this->password = $password;
    $this->cookie = $cookie;
  }

  private function post($url, $body, $headers)
  {
    try {
      $client = new GuzzleHttp\Client($headers);
      $resp = $client->request('POST', $url, [
        'form_params' => $body
      ]);
      $resp = @json_decode($resp->getBody());
      return $resp;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function getLogs()
  {


    $date = new Moment\Moment("now", "Asia/Ho_Chi_Minh");
    // $endDay = $date->format("d/m/Y");
    // $startDay =  $date->subtractDays(1)->format("d/m/Y");
    $endDay = $date->cloning()->addDays(1)->format("d/m/Y");
    $startDay =  $date->subtractDays(1)->format("d/m/Y");

    $body = [
      'jwt' => $this->JWT,
      'account' => $this->AccountNo,
      'username' => $this->username,
      'start' => $startDay,
      'end' => $endDay
    ];

    $headers = [
      'headers' => [
        'Authorization' => 'Bearer ' . $this->JWT,
        'Cookie' => $this->cookie
      ]
    ];

    $get_chiTietGiaoDich = $this->post('http://vcb.lebooks.me/transactions', $body, $headers);
    return $get_chiTietGiaoDich;
  }

  public function login()
  {
    $body = [
      'username' => $this->username,
      'password' => $this->password
    ];
    $resp = $this->post('http://vcb.lebooks.me/auth', $body, []);
    return $resp;
  }
}
