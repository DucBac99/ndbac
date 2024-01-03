<?php

function autofbApi($type, $uid)
{
  try {
    $config = [
      'allow_redirects' => true,
      'headers' => []
    ];

    $body = [
      'query' => [
        'type' => $type,
        'uid' => $uid
      ]
    ];

    $client = new \GuzzleHttp\Client($config);
    $resp = $client->request('GET', 'https://api.autofb.pro/sabo/api.php', $body);
    $respsone = $resp->getBody()->getContents();
  } catch (GuzzleHttp\Exception\ClientException $exception) {
    $respsone = $exception->getResponse()->getBody()->getContents();
  }

  try {
    $json = @json_decode($respsone);
  } catch (Exception $ex) {
    return $respsone;
  }

  if (!is_object($json)) {
    return $respsone;
  }
  return $json;
}
