<?php

namespace ProxyTurn;

class Shoplike
{
  private $keys = [];
  private $api_url = 'http://proxy.shoplike.vn';

  public function __construct($keys = null)
  {
    if ($keys) $this->keys = $keys;
  }

  public function getCurrentProxy()
  {
    $access_token = $this->keys[array_rand($this->keys)];
    try {
      $client = new \GuzzleHttp\Client();
      $resp = $client->request('GET', $this->api_url . '/Api/getCurrentProxy', [
        'query' => [
          'access_token' => $access_token
        ]
      ]);

      $json = @json_decode($resp->getBody());
    } catch (\Exception $ex) {
      return null;
    }
    return !empty($json->status) && $json->status == "success" ? $json->data->proxy : null;
  }

  public function getNewProxy()
  {
    $access_token = $this->keys[array_rand($this->keys)];
    try {
      $client = new \GuzzleHttp\Client();
      $resp = $client->request('GET', $this->api_url . '/Api/getNewProxy', [
        'query' => [
          'access_token' => $access_token
        ]
      ]);
      $json = @json_decode($resp->getBody());
    } catch (\Exception $ex) {
      return null;
    }
    return !empty($json->status) && $json->status == "success" ? $json->data->proxy : null;
  }
}
