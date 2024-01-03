<?php
require_once APPPATH . '/config/common.config.php'; // Common configuration
require_once APPPATH . '/config/db.config.php'; // Database configuration
require_once APPPATH . '/config/i18n.config.php'; // i18n configuration
require_once APPPATH . '/config/cloudflare.config.php'; // cloudflare configuration
require_once APPPATH . '/config/bt.config.php'; // api bt configuration
require_once APPPATH . '/config/token.config.php'; // token api configuration
require_once APPPATH . '/config/redis.config.php'; // redis configuration

// ASCII Secure random crypto key
define("CRYPTO_KEY", "def0000099e19cacdea3aeebc8992bfab49df2cf96333f899fdae1f9f63cd3599e1c253f86c8c5003b63bfd90114d1071f67bbcc8137c868a5c7532a222c2e809154d321");

// General purpose salt
define("NP_SALT", "zvsh0oXn5q7utd4Z");


// Path to instagram sessions directory
define("SESSIONS_PATH", APPPATH . "/sessions");
// Path to temporary files directory
define("TEMP_PATH", ROOTPATH . "/assets/uploads/temp");


// Path to themes directory
define("THEMES_PATH", ROOTPATH . "/inc/themes");
// URI of themes directory
define("THEMES_URL", APPURL . "/inc/themes");


// Path to plugins directory
define("JWT_PATH", APPPATH . "/jwt");
