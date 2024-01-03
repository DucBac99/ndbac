<?php

namespace ProxyStatic;

class ProxyFB
{
  private $list = [];

  public function __construct($list = null)
  {
    if ($list) $this->list = $list;
  }

  public function getProxy()
  {
    $proxy = $this->list[array_rand($this->list)];
    $dump = explode("//", $proxy);
    if (count($dump) != 2) {
      return "";
    }
    $protocol = $dump[0];

    $dump = explode(":", $dump[1]);
    if (count($dump) == 4) {
      $proxy = $protocol . "//" . $dump[2] . ":" . $dump[3] . "@" . $dump[0] . ":" . $dump[1];
    } else {
      return "";
    }
    return $proxy;
  }
}
