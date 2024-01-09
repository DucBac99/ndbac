<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/db.config.php';

$config = [
    'driver' => 'mysql',
    'host' => DB_HOST,
    'database' => DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => DB_ENCODING,
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
];
new \Pixie\Connection('mysql', $config, 'DB');


// Check if SSL Cloudflare enabled
$is_https_buttflare = isset($_SERVER['HTTPS']) ||
  (isset($_SERVER['HTTP_CF_VISITOR']) && $visitor = json_decode($_SERVER['HTTP_CF_VISITOR'])) &&
  $visitor->scheme == 'https';

// Check if SSL enabled.
$ssl = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] && $_SERVER["HTTPS"] != "off"
  ? true
  : false;

// URL of the application root. 
// This is not the URL of the app directory.
$app_url = (($is_https_buttflare || $ssl)  ? "https" : "http")
  . "://"
  . $_SERVER["SERVER_NAME"];




// Required libraries, config files and helpers...
/*
 * CKFinder Configuration File
 *
 * For the official documentation visit https://ckeditor.com/docs/ckfinder/ckfinder3-php/
 */

/*============================ PHP Error Reporting ====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/debugging.html

// Production
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 0);

// Development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
/*============================ General Settings =======================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

$config = array();

/*============================ Enable PHP Connector HERE ==============================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_authentication


$cookie = $_COOKIE["nplh"];
$hash = explode(".", $cookie, 2);
if (count($hash) == 2) {
    $query = DB::table(TABLE_PREFIX . TABLE_USERS)->where('id', '=', (int)$hash[0])->limit(1)->select(["is_active", "password", "id", "role_id"]);
    if ($query->count() == 1) {
        $resp = $query->get();
        $r = $resp[0];
        if ($r->is_active == 1 && md5($r->password) == $hash[1] && in_array($r->role_id, [1, 2])) {
            $config['authentication'] = function () {
                return true;
            };
        }
    }
}
$sites = DB::table(TABLE_PREFIX . TABLE_SITES)
  ->where('domain', '=', $_SERVER["SERVER_NAME"])
  ->limit(1)
  ->select(["domain", "options"])
  ->get();

if (count($sites) == 0) {
  $config['authentication'] = function () {
    return false;
  };
} else {
  $site = $sites[0];
  $site->options = json_decode($sites[0]->options);
}


/*============================ License Key ============================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_licenseKey
$config['licenseName'] = !empty($site->domain) ? $site->domain : '';
$config['licenseKey']  = !empty($site->options->licenseKey) ? $site->options->licenseKey : '';

/*============================ CKFinder Internal Directory ============================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_privateDir

$config['privateDir'] = array(
    'backend' => 'default',
    'tags'   => '.ckfinder/tags',
    'logs'   => '.ckfinder/logs',
    'cache'  => '.ckfinder/cache',
    'thumbs' => '.ckfinder/cache/thumbs',
);

/*============================ Images and Thumbnails ==================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_images

$config['images'] = array(
    'maxWidth'  => 1600,
    'maxHeight' => 1200,
    'quality'   => 80,
    'sizes' => array(
        'small'  => array('width' => 480, 'height' => 320, 'quality' => 80),
        'medium' => array('width' => 600, 'height' => 480, 'quality' => 80),
        'large'  => array('width' => 800, 'height' => 600, 'quality' => 80)
    )
);

/*=================================== Backends ========================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_backends

$config['backends'][] = array(
    'name'         => 'default',
    'adapter'      => 'local',
    'baseUrl'      => $app_url . "/assets/uploads/",
    'root'         => $_SERVER['DOCUMENT_ROOT'] . "/assets/uploads/", // Can be used to explicitly set the CKFinder user files directory.
    'chmodFiles'   => 0777,
    'chmodFolders' => 0755,
    'filesystemEncoding' => 'UTF-8',
);

/*================================ Resource Types =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_resourceTypes

$config['defaultResourceTypes'] = '';

$config['resourceTypes'][] = array(
    'name'              => 'Files', // Single quotes not allowed.
    'directory'         => 'files',
    'maxSize'           => 0,
    'allowedExtensions' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip',
    'deniedExtensions'  => '',
    'backend'           => 'default'
);

$config['resourceTypes'][] = array(
    'name'              => 'Images',
    'directory'         => 'images',
    'maxSize'           => 0,
    'allowedExtensions' => 'bmp,gif,jpeg,jpg,png',
    'deniedExtensions'  => '',
    'backend'           => 'default'
);

/*================================ Access Control =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_roleSessionVar

$config['roleSessionVar'] = 'CKFinder_UserRole';

// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_accessControl
$config['accessControl'][] = array(
    'role'                => '*',
    'resourceType'        => '*',
    'folder'              => '/',

    'FOLDER_VIEW'         => true,
    'FOLDER_CREATE'       => true,
    'FOLDER_RENAME'       => true,
    'FOLDER_DELETE'       => true,

    'FILE_VIEW'           => true,
    'FILE_CREATE'         => true,
    'FILE_RENAME'         => true,
    'FILE_DELETE'         => true,

    'IMAGE_RESIZE'        => true,
    'IMAGE_RESIZE_CUSTOM' => true
);


/*================================ Other Settings =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html

$config['overwriteOnUpload'] = false;
$config['checkDoubleExtension'] = true;
$config['disallowUnsafeCharacters'] = false;
$config['secureImageUploads'] = true;
$config['checkSizeAfterScaling'] = true;
$config['htmlExtensions'] = array('html', 'htm', 'xml', 'js');
$config['hideFolders'] = array('.*', 'CVS', '__thumbs');
$config['hideFiles'] = array('.*');
$config['forceAscii'] = false;
$config['xSendfile'] = false;

// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_debug
$config['debug'] = false;

/*==================================== Plugins ========================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_plugins

$config['pluginsDirectory'] = __DIR__ . '/plugins';
$config['plugins'] = array();

/*================================ Cache settings =====================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_cache

$config['cache'] = array(
    'imagePreview' => 24 * 3600,
    'thumbnails'   => 24 * 3600 * 365,
    'proxyCommand' => 0
);

/*============================ Temp Directory settings ================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_tempDirectory

$config['tempDirectory'] = sys_get_temp_dir();

/*============================ Session Cause Performance Issues =======================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_sessionWriteClose

$config['sessionWriteClose'] = true;

/*================================= CSRF protection ===================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_csrfProtection

$config['csrfProtection'] = true;

/*===================================== Headers =======================================*/
// https://ckeditor.com/docs/ckfinder/ckfinder3-php/configuration.html#configuration_options_headers

$config['headers'] = array();

/*============================== End of Configuration =================================*/

// Config must be returned - do not change it.
return $config;
