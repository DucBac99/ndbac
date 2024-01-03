<?php

namespace ProxyStatic;

class Vitechcheap
{
  private $list = [];

  public function __construct($list = null)
  {
    if ($list) $this->list = $list;
  }

  public function getProxy()
  {
    $proxy = $this->list[array_rand($this->list)];
    return $proxy;
  }
}
