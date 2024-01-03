<?php

/**
 * Main app
 */
class App
{
    protected $router;
    protected $controller;

  // An array of the URL routes
    protected static $routes = [];


/**
   * summary
   */
public function __construct()
{
    $this->controller = new Controller;
}


/**
   * Adds a new route to the App:$routes static variable
   * App::$routes will be mapped on a route 
   * initializes on App initializes
   *
   * Format: ["METHOD", "/uri/", "Controller"]
   * Example: App:addRoute("GET|POST", "/post/?", "Post");
   */
public static function addRoute()
{
    $route = func_get_args();
    if ($route) {
        self::$routes[] = $route;
    }
}

/**
   * Get App::$routes
   * @return array An array of the added routes
   */
public static function getRoutes()
{
    return self::$routes;
}



/**
   * Create database connection
   * @return App
   */
private function db()
{
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
    return $this;
}


/**
   * Check and get authorized user data
   * Define $AuthUser variable
   */
private function auth(SiteModel $Site)
{
    $AuthUser = null;
    if (Input::cookie("nplh")) {
        $hash = explode(".", Input::cookie("nplh"), 2);

        if (count($hash) == 2) {
            $User = Controller::Model("User", (int)$hash[0]);

            if (
                $User->isAvailable() &&
                $User->get("is_active") == 1 &&
                $User->get("site_id") == $Site->get("id") &&
                md5($User->get("password")) == $hash[1]
            ) {
                $AuthUser = $User;

                if (Input::cookie("nplrmm")) {
                    setcookie("nplh", $User->get("id") . "." . md5($User->get("password")), time() + 86400 * 30, "/");
                    setcookie("nplrmm", "1", time() + 86400 * 30, "/");
                }
            }
        }
    } else if (strpos($_SERVER['REQUEST_URI'], '/api') !== false) {
    if (!Input::get("access_token")) {
        jsonecho(array(
            'result' => 0,
            'msg' => 'Token không tồn tại',
        ));
    }

    $hash = explode(".", Input::get("access_token"), 2);
    if (count($hash) != 2) {
        jsonecho(array(
            'result' => 0,
            'msg' => 'Token không tồn tại',
        ));
    }

    $User = Controller::Model("User", (int)$hash[0]);
    if (
        $User->isAvailable() &&
        $User->get("is_active") == 1 &&
        $User->get("site_id") == $Site->get("id") &&
        $User->get("api_key") == $hash[1]
    ) {
        $AuthUser = $User;
    } else {
        jsonecho(array(
            'result' => 0,
            'msg' => 'Token không tồn tại',
        ));
    }
    }

    return $AuthUser;
}


/**
   * Load active theme (skin)
   * @return SiteModel 
   */
private function loadTheme()
{
    $domain = $_SERVER["SERVER_NAME"];
    $AuthSite = Controller::model("Site", $domain);
    $GLOBALS['AuthSite'] = $AuthSite;

    if (!$AuthSite->isAvailable()) {
        header("HTTP/1.0 404 Not Found");
        exit;
    }

    if (!$AuthSite->get("is_active")) {
        header("HTTP/1.0 403 Forbidden");
        exit;
    }

    if ($AuthSite->get("options.maintenance_mode")) {
        header("HTTP/1.0 503 (Service unavailable)");
        exit;
    }

    if (!$AuthSite->get("is_root")) {
        $config_file = active_theme("path") . "/config.php";
        $loader_file = active_theme("path") . "/autoload.php";
        if (!file_exists($config_file) || !file_exists($loader_file)) {
            header("HTTP/1.0 404 Not Found");
        exit;
    }

      // Load and check config file
        $config = include $config_file;
        if (!isset($config["theme_name"]) || $config["theme_name"] != $AuthSite->get("theme")) {
            header("HTTP/1.0 403 Forbidden");
        exit;
    }
      // Load the them
    require_once $loader_file;
    }

    return $AuthSite;
}



  /**
   * Define ACTIVE_LANG constant
   * Include language strings
   */
  private function i18n()
  {
    // Validate found language code
    $active_lang = Config::get("default_applang");
    define("ACTIVE_LANG", $active_lang);
    @setcookie("lang", ACTIVE_LANG, time() + 30 * 86400, "/");


    $Translator = new Gettext\Translator;

    // Load app. locale
    $path = APPPATH . "/locale/" . ACTIVE_LANG . "/messages.po";
    if (file_exists($path)) {
      $translations = Gettext\Translations::fromPoFile($path);
      $Translator->loadTranslations($translations);
    }

    // Load theme locale
    $path = active_theme("path") . "/locale/" . ACTIVE_LANG . "/messages.po";
    if (file_exists($path)) {
      $translations = Gettext\Translations::fromPoFile($path);
      $Translator->loadTranslations($translations);
    }

    $Translator->register(); // Register global functions

    // Set other library locales
    try {
      \Moment\Moment::setLocale(str_replace("-", "_", ACTIVE_LANG));
    } catch (Exception $e) {
      // Couldn't load locale
      // There is nothing to do here,
      // Fallback to default language
    }
  }


  /**
   * Analyze route and load proper controller
   * @return App
   */
  private function route(SiteModel $AuthSite)
  {
    // Initialize the router
    $router = new AltoRouter();
    $router->setBasePath(BASEPATH);

    // Load plugin/theme routes first
    // TODO: Update router.map in modules to App::addRoute();
    $GLOBALS["_ROUTER_"] = $router;
    \Event::trigger("router.map", "_ROUTER_");
    $router = $GLOBALS["_ROUTER_"];

    // Load internal routes
    $this->addInternalRoutes();

    if ($AuthSite->get("is_root")) {
      // Load global routes
      include APPPATH . "/inc/routes.inc.php";
    }

    // Map the routes
    $router->addRoutes(App::getRoutes());

    // Match the route
    $route = $router->match();
    $route = json_decode(json_encode($route));

    if ($route) {
      if (is_string($route->target)) {
        $controller = $route->target . "Controller";
      } else {
        require_once active_theme("path") . "/controllers/" . str_replace('\\', DIRECTORY_SEPARATOR, $route->target[1]) . "Controller.php";
        $controller = $route->target[0] . '\\' . $route->target[1] . "Controller";
      }
    } else {
      header("HTTP/1.0 404 Not Found");
      $controller = "IndexController";
    }

    $this->controller = new $controller;
    $this->controller->setVariable("Route", $route);
  }

  /**
   * Map the routes which are for 
   * internal use only.
   */
  private function addInternalRoutes()
  {
    // Cron
    App::addRoute("GET", "/cron/?", "Cron\Default");
    App::addRoute("GET", "/cron/check/?", "Cron\Check");
    App::addRoute("GET", "/cron/topup/?", "Cron\Topup");

    // Helpers
    App::addRoute("POST", "/helpers/?", "Helpers");
  }

  /**
   * Process
   */
  public function process()
  {

    /**
     * Create database connection
     */
    $this->db();

    /**
     * Load active theme
     */
    $AuthSite = $this->loadTheme();


    /**
     * Auth.
     */
    $AuthUser = $this->auth($AuthSite);

    /**
     * Analyze the route
     */
    $this->route($AuthSite);
    $this->controller->setVariable("AuthUser", $AuthUser);
    $this->controller->setVariable("AuthSite", $AuthSite);


    /**
     * Init. locales
     */
    $this->i18n();


    $this->controller->process();
  }
}
