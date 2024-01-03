<?php

/**
 * Define database credentials
 */
define("DB_HOST", $_ENV['DB_HOST']);
define("DB_NAME", $_ENV['DB_NAME']);
define("DB_USER", $_ENV['DB_USER']);
define("DB_PASS", $_ENV['DB_PASS']);
define("DB_ENCODING", $_ENV['DB_ENCODING']); // DB connnection charset


/**
 * Define DB tables
 */
define("TABLE_PREFIX", "tb_");

// Set table names without prefix
define("TABLE_USERS", "users");
define("TABLE_ORDERS", "orders");
define("TABLE_ORDER_GROUPS", "order_groups");
define("TABLE_VIP_ORDERS", "vip_orders");
define("TABLE_NEWFEEDS", "newsfeed");
define("TABLE_NEWFEED_ROLES", "newsfeed_roles");

define("TABLE_PAYMENTS", "payments");
define("TABLE_SITES", "sites");
define("TABLE_ROLES", "roles");
define("TABLE_FLUCTUATIONS", "fluctuations");

define("TABLE_SERVICES", "services");
define("TABLE_SERVICE_SETTINGS", "service_settings");
define("TABLE_SERVICE_TITLES", "service_titles");
define("TABLE_INTERACT_LOGS", "interact_logs");

define("TABLE_SERVERS", "servers");
define("TABLE_THEMES", "themes");

define("TABLE_ORDER_LOGS", "order_logs");
define("TABLE_WARRANTY_LOGS", "warranty_logs");
define("TABLE_OPTIONS", "options");

define("TABLE_ORDER_COMMENTS", "order_comments");
