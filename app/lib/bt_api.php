<?php

/**
 * 宝塔API接口示例Demo
 * 仅供参考，请根据实际项目需求开发，并做好安全处理
 * date 2018/12/12
 * author 阿良
 */
class bt_api
{
  private $BT_KEY = BT_KEY;  //接口密钥
  private $BT_PANEL = BT_PANEL;     //面板地址
  private $SESSIONS_PATH = SESSIONS_PATH;     //面板地址

  //如果希望多台面板，可以在实例化对象时，将面板地址与密钥传入
  public function __construct($bt_panel = null, $bt_key = null, $sessions_path = null)
  {
    if ($bt_panel) $this->BT_PANEL = $bt_panel;
    if ($bt_key) $this->BT_KEY = $bt_key;
    if ($sessions_path) $this->SESSIONS_PATH = $sessions_path;
  }

  //示例取面板日志	
  public function AddSite($domain, $path)
  {
    $url = $this->BT_PANEL . '/site?action=AddSite';
    $p_data = $this->GetKeyData();

    $p_data['webname'] = json_encode([
      'domain'        => $domain,
      'domainlist'    => [],
      'count'         => 0,
    ]);
    $p_data['path'] = $path;
    $p_data['type_id'] = 0;
    $p_data['type'] = 'PHP';
    $p_data['version'] = '74';
    $p_data['port'] = '80';
    $p_data['ps'] = '';
    $p_data['ftp'] = false;
    $p_data['sql'] = false;

    $p_data['codeing'] = 'utf8';
    $p_data['set_ssl'] = 0;
    $p_data['force_ssl'] = 0;

    $result = $this->HttpPostCookie($url, $p_data);
    $data = json_decode($result);
    return $data;
  }

  public function deleteSite($domain, $id)
  {
    $url = $this->BT_PANEL . '/site?action=DeleteSite';
    $p_data = $this->GetKeyData();

    $p_data['ftp']        = "0";
    $p_data['database']   = "0";
    $p_data['path']       = "0";
    $p_data['id']         = $id;
    $p_data['webname']    = $domain;

    $result = $this->HttpPostCookie($url, $p_data);
    $data = json_decode($result);
    return $data;
  }

  public function AddDomain($domain, $webname, $id)
  {
    $url = $this->BT_PANEL . '/site?action=AddDomain';
    $p_data = $this->GetKeyData();

    $p_data['id']        = $id;
    $p_data['webname']   = $webname;
    $p_data['domain']    = $domain . ":80";

    $result = $this->HttpPostCookie($url, $p_data);
    $data = json_decode($result);
    return $data;
  }

  public function SaveFileBody($path, $data, $encoding = 'utf-8', $type = 0)
  {
    $url = $this->BT_PANEL . '/files?action=SaveFileBody';
    if ($type) {
      $path_dir = $path;
    } else {
      $path_dir = '/www/server/panel/vhost/rewrite/' . $path . '.conf';
    }
    $p_data = $this->GetKeyData();
    $p_data['path'] = $path_dir;
    $p_data['data'] = $data;
    $p_data['encoding'] = $encoding;
    $result = $this->HttpPostCookie($url, $p_data);

    $data = json_decode($result);
    return $data;
  }


  public function AddCrontab($name, $urladdress)
  {
    $url = $this->BT_PANEL . '/crontab?action=AddCrontab';
    $p_data = $this->GetKeyData();

    $p_data['name'] = $name;
    $p_data['type'] = "minute-n";
    $p_data['where1'] = "3";
    $p_data['hour'] = '';
    $p_data['minute'] = '';
    $p_data['week'] = '';
    $p_data['sType'] = "toUrl";
    $p_data['sBody'] = null;
    $p_data['sName'] = "";
    $p_data['backupTo'] = "localhost";
    $p_data['save'] = "";
    $p_data['urladdress'] = $urladdress;
    $p_data['save_local'] = 0;
    $p_data['notice'] = null;
    $p_data['notice_channel'] = null;

    $result = $this->HttpPostCookie($url, $p_data);
    $data = json_decode($result);
    return $data;
  }




  /**
   * 构造带有签名的关联数组
   */
  private function GetKeyData()
  {
    $now_time = time();
    $p_data = array(
      'request_token'  =>  md5($now_time . '' . md5($this->BT_KEY)),
      'request_time'  =>  $now_time
    );
    return $p_data;
  }


  /**
   * 发起POST请求
   * @param String $url 目标网填，带http://
   * @param Array|String $data 欲提交的数据
   * @return string
   */
  private function HttpPostCookie($url, $data, $timeout = 60)
  {

    //定义cookie保存位置
    $cookie_file = $this->SESSIONS_PATH . '/' . md5($this->BT_PANEL) . '.cookie';

    if (!file_exists($cookie_file)) {
      $fp = fopen($cookie_file, 'w+');
      fclose($fp);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }
}
