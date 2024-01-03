<?php

/**
 * Bank class to login and get transactions
 * 
 * @author ngdanghau <ngdghau201@gmail.com>
 */
class OneAPI
{
  private $apiKey;
  private $bank_code;

  public function __construct($bank_code, $apiKey)
  {
    $this->bank_code = $bank_code;
    $this->apiKey = $apiKey;
  }

  private function post($url, $body, $headers)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $server_output = curl_exec($ch);
    $resp = @json_decode($server_output);
    curl_close($ch);

    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new Exception("Cannot parse JSON");
    }
    return $resp;
  }

  public function getTransactions($AccountNo, $accessToken)
  {
    $endDay = new DateTime();
    $endDay->modify("+1 day");

    $startDay = new DateTime();
    $startDay->modify("-1 day");

    $body = [
      'accessToken' => $this->apiKey,
      'accountNo' => $AccountNo,
      'bank_code' => $this->bank_code,
      'startDate' => $startDay->getTimestamp(),
      'endDate' => $endDay->getTimestamp()
    ];

    $headers = [
      'Authorization: JWT ' . $accessToken,
      'Content-Type: application/x-www-form-urlencoded'
    ];

    $get_chiTietGiaoDich = $this->post('https://oneapi.hksolutions.co/api/transactions', $body, $headers);
    return $get_chiTietGiaoDich;
  }

  public function login($username, $password)
  {
    $body = [
      'accessToken' => $this->apiKey,
      'username' => $username,
      'password' => $password,
      'bank_code' => $this->bank_code
    ];

    $headers = [
      'Content-Type: application/x-www-form-urlencoded'
    ];

    $resp = $this->post('https://oneapi.hksolutions.co/api/login', $body, $headers);
    return $resp;
  }
}
