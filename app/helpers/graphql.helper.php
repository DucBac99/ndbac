<?php

function curl($url, $method, $data, $headers, $proxy = null)
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 300,
    CURLOPT_CONNECTTIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_PROXY => $proxy
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return $response;
}

function curlWeb($url, $data, $method, $proxy = null)
{

  $headers = array(
    'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
    'Content-Type: application/x-www-form-urlencoded',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    'accept-encoding: gzip, deflate, br',
    'accept-language: en',
    'cache-control: max-age=0',
    'cookie: sb=bHWLY7GglacvIsSUKXhZlLzA; datr=bHWLY_nDI7LwTb5Hzpo1p9pQ; dpr=2; m_pixel_ratio=2; fr=0g7eD7lyA2cdqRcQo..Bji3Vs.Kj.AAA.0.0.Bji3xS.AWVjsjLBNRE; wd=2560x618'
  );

  $resp = curl($url, $method, http_build_query($data), $headers, $proxy);
  if ($resp == "") {
    throw new Exception('Account checkpoint');
  } else if (strpos($resp, "Temporarily Blocked") !== false) {
    throw new Exception('Account block');
  } else if (strpos($resp, "Account locked") !== false) {
    throw new Exception('Account checkpoint');
  } else if (strpos($resp, 'Something Went Wrong') !== false) {
    throw new Exception('Something Went Wrong');
  } else if (strpos($resp, 'Login approval needed') !== false) {
    throw new Exception('Account checkpoint');
  }

  try {
    $json = @json_decode($resp);
  } catch (Exception $ex) {
    return $resp;
  }

  if (!is_object($json)) {
    return $resp;
  }
  return $json;
}

function graphqlWeb($data, $proxy = null)
{
  $headers = array(
    'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 14_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/18E199 [FBAN/FBIOS;FBAV/368.0.0.24.107;FBBV/371860683;FBDV/iPhone8,1;FBMD/iPhone;FBSN/iOS;FBSV/14.5;FBSS/2;FBID/phone;FBLC/en_US;FBOP/5;FBRV/376602186]',
    // 'accept: */*',
    // 'accept-encoding: gzip, deflate, br',
    // 'accept-language: en,vi-VN;q=0.9,vi;q=0.8,fr-FR;q=0.7,fr;q=0.6,en-US;q=0.5,hy;q=0.4',
    // 'content-length: 1715',
    // 'content-type: application/x-www-form-urlencoded',
    // 'origin: https://www.facebook.com',
    // 'referer: https://www.facebook.com/',
    // 'sec-ch-prefers-color-scheme: dark',
    // 'sec-ch-ua: "Google Chrome";v="107", "Chromium";v="107", "Not=A?Brand";v="24"',
    // 'sec-ch-ua-mobile: ?0',
    // 'sec-ch-ua-platform: "macOS"',
    // 'sec-fetch-dest: empty',
    // 'sec-fetch-mode: cors',
    // 'sec-fetch-site: same-origin',
    // 'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
    // 'viewport-width: 2560',
    // 'x-fb-friendly-name: ComposerLinkAttachmentPreviewQuery',
    // 'x-fb-lsd: nMoKocX6pkP9ZqSe-IMC99',
    // 'Origin: https://www.facebook.com',
    // 'Referer: https://www.facebook.com/',
    'Content-Type: application/x-www-form-urlencoded'
  );

  if (isset($data['variables'])) {
    $data['variables'] = json_encode($data['variables']);
  }

  $resp = curl('https://www.facebook.com/api/graphql/', 'POST', http_build_query($data), $headers, $proxy);

  if ($resp == "") {
    throw new Exception('Account Checkpoint');
  }

  if (is_string($resp)) {
    if (strpos($resp, 'Something Went Wrong') !== false) {
      throw new Exception('Something Went Wrong');
    } else if (strpos($resp, 'Login approval needed') !== false) {
      throw new Exception('Account Checkpoint');
    }
  }


  try {
    $json = @json_decode($resp);
  } catch (Exception $ex) {
    return $resp;
  }

  if (!is_object($json)) {
    return $resp;
  }
  return $json;
}

function graphqlWebApi($data, $proxy = null, $cookie = '')
{
  try {
    $config = [
      'allow_redirects' => true,
      'timeout' => 5,
      'headers' => [
        'Host' => 'www.facebook.com',
        'Cookie' => 'sb=ev-LYx5GF8ZTsbynYjfMkeCM; wd=2560x1336; datr=ev-LY3PSrE7A9xxyUGr8IBmM; dpr=2; fr=0Bm6VORuACGsM1O73..Bji_96.Fk.AAA.0.0.BjjEsb.AWWNFCycWws',
        'Sec-Ch-Ua' => '"Chromium";v="107", "Not=A?Brand";v="24"',
        'Sec-Ch-Ua-Mobile' => '?0',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.5304.107 Safari/537.36',
        'Viewport-Width' => '2560',
        'Content-Type' => 'application/x-www-form-urlencoded',
        'X-Fb-Lsd' => $data['lsd'],
        'X-Fb-Friendly-Name' => $data['fb_api_req_friendly_name'],
        'Sec-Ch-Prefers-Color-Scheme' => 'light',
        'Sec-Ch-Ua-Platform' => '"macOS"',
        'Accept' => '*/*',
        'Origin' => 'https://www.facebook.com',
        'Sec-Fetch-Site' => 'same-origin',
        'Sec-Fetch-Mode' => 'cors',
        'Sec-Fetch-Dest' => 'empty',
        'Referer' => 'https://www.facebook.com/',
        'Accept-Encoding' => 'gzip, deflate',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Cookie' => $cookie
      ]
    ];
    $client = new \GuzzleHttp\Client($config);
    $resp = $client->request('POST', 'https://www.facebook.com/api/graphql/', [
      'form_params' => $data,
      'proxy' => $proxy ? $proxy : ''
    ]);
    $response = $resp->getBody()->getContents();
  } catch (GuzzleHttp\Exception\ClientException $exception) {
    $response = $exception->getResponse()->getBody(true);
  }

  if (is_string($resp)) {
    if (strpos($resp, 'Something Went Wrong') !== false) {
      throw new Exception('Something Went Wrong');
    } else if (strpos($resp, 'Login approval needed') !== false) {
      throw new Exception('Account Checkpoint');
    }
  }

  try {
    $json = @json_decode($response);
  } catch (Exception $ex) {
    return $response;
  }

  if (!is_object($json)) {
    return $response;
  }
  return $json;
}

function curlWebApi($url, $data, $method, $proxy = null)
{
  try {
    $config = [
      'timeout' => 5,
      'allow_redirects' => true,
      'headers' => [
        'Host' => 'www.facebook.com',
        'Cookie' => 'fr=0Bm6VORuACGsM1O73..Bji_96.Fk.AAA.0.0.Bji_96.AWUoTEpfZng; sb=ev-LYx5GF8ZTsbynYjfMkeCM; wd=2560x1336; datr=ev-LY3PSrE7A9xxyUGr8IBmM; dpr=2',
        'Sec-Ch-Ua' => '"Chromium";v="107", "Not=A?Brand";v="24"',
        'Sec-Ch-Ua-Mobile' => '?0',
        'Sec-Ch-Ua-Platform' => '"macOS"',
        'Upgrade-Insecure-Requests' => '1',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.5304.107 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'Sec-Fetch-Site' => 'none',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-User' => '?1',
        'Sec-Fetch-Dest' => 'document',
        'Accept-Encoding' => 'gzip, deflate',
        'Accept-Language' => 'en-US,en;q=0.9',
      ]
    ];
    if ($method == 'POST') {
      $body = [
        'form_params' => $data,
        'proxy' => $proxy ? $proxy : ''
      ];
    } else if ($method == 'GET') {
      $body = [
        'query' => $data,
        'proxy' => $proxy ? $proxy : ''
      ];
    }

    $client = new \GuzzleHttp\Client($config);
    $resp = $client->request($method, $url, $body);
    $respsone = $resp->getBody()->getContents();
  } catch (GuzzleHttp\Exception\ClientException $exception) {
    $respsone = $exception->getResponse()->getBody()->getContents();
  }

  if (is_string($respsone)) {
    if (strpos($respsone, 'Something Went Wrong') !== false) {
      throw new Exception('Something Went Wrong');
    } else if (strpos($respsone, 'Login approval needed') !== false) {
      throw new Exception('Account Checkpoint');
    }
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
