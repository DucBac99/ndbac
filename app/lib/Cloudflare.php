<?php

class Cloudflare
{
  static public function addDomain($domain)
  {
    try {
      $headers = [
        'headers' => [
          'Authorization' => 'Bearer ' . CLOUDFLARE_API_KEY,
          'Content-Type' => 'application/json'
        ]
      ];
      $client = new GuzzleHttp\Client($headers);
      $resp = $client->request('POST', CLOUDFLARE_ENDPOINT . "/zones", [
        "json" => array(
          "name" => $domain,
          "account" => array("id" => CLOUDFLARE_ACCOUNT_ID),
          "jump_start" => true,
          "type" => "full"
        )
      ]);
      $resp = @json_decode($resp->getBody());

      return $resp;
    } catch (GuzzleHttp\Exception\ClientException $ex) {
      $response = $ex->getResponse();
      $response = @json_decode($response->getBody());
      return $response;
    }
  }

  static public function removeDomain($zone_id)
  {
    try {
      $headers = [
        'headers' => [
          'Authorization' => 'Bearer ' . CLOUDFLARE_API_KEY,
          'Content-Type' => 'application/json'
        ]
      ];
      $client = new GuzzleHttp\Client($headers);
      $resp = $client->request('DELETE', CLOUDFLARE_ENDPOINT . "/zones/" . $zone_id);
      $resp = @json_decode($resp->getBody());

      return $resp;
    } catch (GuzzleHttp\Exception\ClientException $ex) {
      $response = $ex->getResponse();
      $response = @json_decode($response->getBody());
      return $response;
    }
  }

  static public function checkDomain($zone_id)
  {
    try {
      $headers = [
        'headers' => [
          'Authorization' => 'Bearer ' . CLOUDFLARE_API_KEY,
          'Content-Type' => 'application/json'
        ]
      ];
      $client = new GuzzleHttp\Client($headers);
      $resp = $client->request('PUT', CLOUDFLARE_ENDPOINT . "/zones/" . $zone_id . "/activation_check");
      $resp = @json_decode($resp->getBody());

      return $resp;
    } catch (GuzzleHttp\Exception\ClientException $ex) {
      $response = $ex->getResponse();
      $response = @json_decode($response->getBody());
      return $response;
    }
  }



  static public function addDNSRecord($zone_id, $data)
  {
    try {
      $headers = [
        'headers' => [
          'Authorization' => 'Bearer ' . CLOUDFLARE_API_KEY,
          'Content-Type' => 'application/json'
        ]
      ];
      $client = new GuzzleHttp\Client($headers);
      $resp = $client->request('POST', CLOUDFLARE_ENDPOINT . "/zones/" . $zone_id . "/dns_records", [
        "json" => $data
      ]);
      $resp = @json_decode($resp->getBody());

      return $resp;
    } catch (GuzzleHttp\Exception\ClientException $ex) {
      $response = $ex->getResponse();
      $response = @json_decode($response->getBody());
      return $response;
    }
  }
}
