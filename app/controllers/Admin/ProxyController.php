<?php

namespace Admin;

use Input;
use Controller;
use DB;

/**
 * Proxy Controller
 */
class ProxyController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $Route = $this->getVariable("Route");
    $AuthUser = $this->getVariable("AuthUser");

    // Auth
    if (!$AuthUser || !$AuthUser->isAdmin()) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    $page = isset($Route->params->page) ? $Route->params->page : "shoplike";

    $list = "";
    switch ($page) {
      case 'shoplike':
        $list = join("\n", json_decode(get_option("list_key_proxy_shoplike"), true));
        break;
      case 'proxyfb':
        $list = join("\n", json_decode(get_option("list_proxy_static_proxyfb"), true));
        break;
      case 'vitechcheap':
        $list = join("\n", json_decode(get_option("list_proxy_static_vitechcheap"), true));
        break;

      default:
        # code...
        break;
    }
 
    $this->setVariable("page", $page)
      ->setVariable("list", $list);

    if (Input::post("action") == "save") {
      $this->save();
    }

    $this->view("admin/proxy");
  }


  /**
   * Save changes
   * @return boolean 
   */
  private function save()
  {
    $page = $this->getVariable("page");

    $method = "save";
    $parts = explode("-", $page);
    foreach ($parts as $p) {
      $method .= ucfirst(strtolower($p));
    }

    return $this->$method();
  }


  /**
   * Save shoplike
   * @return boolean 
   */
  private function saveShoplike()
  {
    $AuthSite = $this->getVariable("AuthSite");
    $do_save = false;


    if (!is_null(Input::post("list"))) {
      save_option("list_key_proxy_shoplike", json_encode(preg_split("/\\r\\n|\\r|\\n/", Input::post("list"))));
    }

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }

  /**
   * Save Proxyfb
   * @return boolean 
   */
  private function saveProxyfb()
  {
    $AuthSite = $this->getVariable("AuthSite");
    $do_save = false;


    if (!is_null(Input::post("list"))) {
      save_option("list_proxy_static_proxyfb", json_encode(preg_split("/\\r\\n|\\r|\\n/", Input::post("list"))));
    }

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }

  /**
   * Save Vitechcheap
   * @return boolean 
   */
  private function saveVitechcheap()
  {
    $AuthSite = $this->getVariable("AuthSite");
    $do_save = false;


    if (!is_null(Input::post("list"))) {
      save_option("list_proxy_static_vitechcheap", json_encode(preg_split("/\\r\\n|\\r|\\n/", Input::post("list"))));
    }

    $this->resp->result = 1;
    $this->resp->msg = "Đã lưu thay đổi";
    $this->jsonecho();

    return $this;
  }
}
