<?php
// Language slug
// 
// Will be used theme routes
$langs = [];
foreach (Config::get("applangs") as $l) {
  if (!in_array($l["code"], $langs)) {
    $langs[] = $l["code"];
  }

  if (!in_array($l["shortcode"], $langs)) {
    $langs[] = $l["shortcode"];
  }
}
$langslug = $langs ? "[" . implode("|", $langs) . ":lang]" : "";


/**
 * Theme Routes
 */

// Index (Landing Page)
// 
// Replace "Index" with "Login" to completely disable Landing page 
// After this change, Login page will be your default landing page
// 
// This is useful in case of self use, or having different 
// landing page in different address. For ex: you can install the script
// to subdirectory or subdomain of your wordpress website.
App::addRoute("GET|POST", "/", "Index");
// Login
App::addRoute("GET|POST", "/login/?", "Auth\Login");

// Signup
// 
//  Remove or comment following line to completely 
//  disable signup page. This might be useful in case 
//  of self use of the script
App::addRoute("GET|POST", "/signup/?", "Auth\Signup");

// Logout
App::addRoute("GET", "/logout/?", "Auth\Logout");

// Recovery
App::addRoute("GET|POST", "/recovery/?", "Auth\Recovery");
App::addRoute("GET|POST", "/recovery/[i:id].[a:hash]/?", "Auth\PasswordReset");



/**
 * App Routes
 */

// Settings
$settings_pages = [
  "site", "logotype", "other", "recaptcha",

  "topup", "smtp", "order"
];
App::addRoute("GET|POST", "/settings/[" . implode("|", $settings_pages) . ":page]?/?", "Settings");


// Users
App::addRoute("GET|POST", "/users/?", "Users\List");
// New User
App::addRoute("GET|POST", "/users/new/?", "Users\Edit");
// Statistic User
App::addRoute("GET|POST", "/users/[i:id]/statistic/?", "Users\Statistic");
// Edit User
App::addRoute("GET|POST", "/users/[i:id]/?", "Users\Edit");


// Roles
App::addRoute("GET|POST", "/roles/?", "Roles");
// New Role
App::addRoute("GET|POST", "/roles/new/?", "Role");
// Edit Role
App::addRoute("GET|POST", "/roles/[i:id]/?", "Role");

// Services
App::addRoute("GET|POST", "/services/?", "Services\List");
// New Service
App::addRoute("GET|POST", "/services/new/?", "Services\Item");
// Edit Server
App::addRoute("POST", "/services/[i:id]/servers/?", "Services\Servers");
// Edit Service Pricing
App::addRoute("GET|POST", "/services/[i:id]/price/?", "Services\Pricing");
// Edit Service
App::addRoute("GET|POST", "/services/[i:id]/?", "Services\Item");




require(APPPATH . "/inc/group_orders.inc.php");
$group_order_pages = array_map(function ($item) {
  return $item["idname"];
}, $group_orders);
// List Order
App::addRoute("GET|POST", "/orders/[" . implode("|", $group_order_pages) . ":page]/buff-[:service_name]/?", "Orders\Buff\List");
// List VIP
App::addRoute("GET|POST", "/orders/[" . implode("|", $group_order_pages) . ":page]/vip-[:service_name]/?", "Orders\Vip\List");

// New Order
App::addRoute("GET|POST", "/orders/[" . implode("|", $group_order_pages) . ":page]/buff-[:service_name]/new/?", "Orders\Buff\Item");
// Item Order
App::addRoute("GET|POST", "/orders/[" . implode("|", $group_order_pages) . ":page]/buff-[:service_name]/[i:id]/?", "Orders\Buff\Item");
// New VIP Order
App::addRoute("GET|POST", "/orders/[" . implode("|", $group_order_pages) . ":page]/vip-[:service_name]/new/?", "Orders\Vip\Item");
// Item VIP Order
App::addRoute("GET|POST", "/orders/[" . implode("|", $group_order_pages) . ":page]/vip-[:service_name]/[i:id]/?", "Orders\Vip\Item");

// Sites
App::addRoute("GET|POST", "/sites/?", "Sites\List");
// New Site
App::addRoute("GET|POST", "/sites/new/?", "Sites\Edit");
// Statistic Site
App::addRoute("GET|POST", "/sites/[i:id]/statistic/?", "Sites\Statistic");
// Edit Site
App::addRoute("GET|POST", "/sites/[i:id]/?", "Sites\Edit");

// Servers
App::addRoute("GET|POST", "/servers/?", "Admin\Servers");
// New Server
App::addRoute("GET|POST", "/servers/new/?", "Admin\Server");
// Edit Server
App::addRoute("GET|POST", "/servers/[i:id]/?", "Admin\Server");


// Themes
App::addRoute("GET|POST", "/themes/?", "Admin\Themes");
// Edit Effects
App::addRoute("GET|POST", "/themes/effects?", "Admin\Effects");
// New Theme
App::addRoute("GET|POST", "/themes/new/?", "Admin\Theme");
// Edit Theme
App::addRoute("GET|POST", "/themes/[i:id]/?", "Admin\Theme");

$proxy_pages = [
  "shoplike", "proxyfb", "vitechcheap"
];
// Proxy
App::addRoute("GET|POST", "/proxy/[" . implode("|", $proxy_pages) . ":page]?", "Admin\Proxy");



// Dashboard
App::addRoute("GET|POST", "/dashboard/?", "Dashboard\Dashboard");

App::addRoute("GET|POST", "/analytics/?", "Dashboard\Analytics");



// Profile
App::addRoute("GET|POST", "/profile/?", "Profile\Profile");
App::addRoute("GET|POST", "/payment-history/?", "Profile\PaymentHistory");
App::addRoute("GET|POST", "/fluctuations/?", "Profile\Fluctuations");
App::addRoute("GET|POST", "/site-agency/?", "Profile\SiteAgency");

// Topup
App::addRoute("GET|POST", "/topup/?", "Profile\Topup");

// About Pricing
App::addRoute("GET|POST", "/pricing-details/?", "PricingDetails");

// API
App::addRoute("POST", "/api-docs/?", "API\Docs");
App::addRoute("GET|POST", "/api/servers/?", "API\Servers");
App::addRoute("GET|POST", "/api/services/?", "API\Services");
App::addRoute("GET|POST", "/api/balance/?", "API\Balance");
App::addRoute("GET", "/api/buff-orders/?", "API\Buff\Orders");
App::addRoute("GET", "/api/vip-orders/?", "API\Vip\Orders");
App::addRoute("GET|POST", "/api/vip-order/?", "API\Vip\Order");
App::addRoute("GET|POST", "/api/buff-order/?", "API\Buff\Order");

// API Private
App::addRoute("GET|POST", "/system/?", "API_Private\BuffOrders");
App::addRoute("GET|POST", "/system/vip/?", "API_Private\VipOrders");
App::addRoute("GET|POST", "/system/group/?", "API_Private\GroupOrders");
App::addRoute("GET|POST", "/system/logs/?", "API_Private\Logs");


// Email verification
App::addRoute("GET|POST", "/verification/email/[i:id].[a:hash]?/?", "Auth\EmailVerification");
