<?php

use ProxyStatic\ProxyFB;
use ProxyStatic\Vitechcheap;
use ProxyTurn\Shoplike;

use ServerApi\TraoDoiSub;
/**
 * Helpers Controller
 */
class HelpersController extends Controller
{
  /**
   * Process
   */
  public function process()
  {
    $AuthUser = $this->getVariable("AuthUser");
    // Auth
    if (!$AuthUser) {
      header("Location: " . APPURL . "/login");
      exit;
    }

    if (Input::post("action") == "get_uid") {
      // $proxy = $this->getProxy();
      // $check = GraphQL::get_uid(Input::post("url"), Input::post("idname"), $proxy);
      $traoDoiSub = new TraoDoiSub('', '', '');
      $check = $traoDoiSub->checkUid(Input::post("url"));
      jsonecho($check);
    }
  }

  protected function getProxy()
  {
    $type_proxy = get_option("type_proxy_for_order");
    if ($type_proxy == "proxyfb") {
      $proxyfb = new ProxyFB(json_decode(get_option("list_proxy_static_proxyfb"), true));
      $proxy = $proxyfb->getProxy();
    } else if ($type_proxy == "vitechcheap") {
      $vitechcheap = new Vitechcheap(json_decode(get_option("list_proxy_static_vitechcheap"), true));
      $proxy = $vitechcheap->getProxy();
    } else if ($type_proxy == "shoplike") {
      $shoplike = new Shoplike(json_decode(get_option("list_key_proxy_shoplike"), true));
      $proxy = $shoplike->getNewProxy();
      if (!$proxy) {
        $proxy = $shoplike->getCurrentProxy();
      };
    }
    return $proxy;
  }
}
