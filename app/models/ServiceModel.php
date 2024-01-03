<?php

/**
 * Service Model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */

class ServiceModel extends DataEntry
{
  /**
   * Extend parents constructor and select entry
   * @param mixed $uniqid Value of the unique identifier
   */
  public function __construct($uniqid = 0)
  {
    parent::__construct();
    $this->select($uniqid);
  }



  /**
   * Select entry with uniqid
   * @param  int|string $uniqid Value of the any unique field
   * @return self       
   */
  public function select($uniqid)
  {
    $AuthSite = $GLOBALS['AuthSite'];
    if (is_int($uniqid) || ctype_digit($uniqid)) {
      $col = $uniqid > 0 ? "id" : null;
    } else {
      $col = "idname";
    }

    if ($col) {
      // $subQuery = DB::table(TABLE_PREFIX . TABLE_SERVICE_SETTINGS)
      // ->select(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . '.title')
      // ->where(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . '.service_id', '=', DB::raw("`" . TABLE_PREFIX . TABLE_SERVICES . "`.`id`"))
      // ->where(TABLE_PREFIX . TABLE_SERVICE_SETTINGS . '.site_id', '=', DB::raw($AuthSite->get("id")));

      $query = DB::table(TABLE_PREFIX . TABLE_SERVICES)
        ->leftJoin(
          TABLE_PREFIX . TABLE_SERVICE_TITLES,
          function ($table) use ($AuthSite) {
            $table->on(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.service_id', '=', TABLE_PREFIX . TABLE_SERVICES . '.id');
            $table->on(TABLE_PREFIX . TABLE_SERVICE_TITLES . '.site_id', '=', DB::raw($AuthSite->get("id")));
          }
        )
        ->where(TABLE_PREFIX . TABLE_SERVICES . "." . $col, "=", $uniqid)
        ->limit(1)
        ->select(TABLE_PREFIX . TABLE_SERVICES . ".*")
        ->select([
          DB::raw(TABLE_PREFIX . TABLE_SERVICE_TITLES . ".title as title_extra"),
        ]);
      // ->select(DB::subQuery($subQuery, 'title_extra'));
      if ($query->count() == 1) {
        $resp = $query->get();
        $r = $resp[0];

        foreach ($r as $field => $value)
          $this->set($field, $value);

        $this->is_available = true;
      } else {
        $this->data = array();
        $this->is_available = false;
      }
    }

    return $this;
  }


  /**
   * Extend default values
   * @return self
   */
  public function extendDefaults()
  {
    $defaults = array(
      "title" => "",
      "group" => "facebook",
      "icon" => "",
      "warranty" => 0,
      "idname" => "",
      "is_public" => 1,
      "is_maintaince" => 0,
      "note" => "",
      "speed" => "",
      "max_hold" => 0,
      "created_at" => date("Y-m-d H:i:s"),
      "updated_at" => date("Y-m-d H:i:s")
    );


    foreach ($defaults as $field => $value) {
      if (is_null($this->get($field)))
        $this->set($field, $value);
    }
  }


  /**
   * Insert Data as new entry
   */
  public function insert()
  {
    if ($this->isAvailable())
      return false;

    $this->extendDefaults();

    $id = DB::table(TABLE_PREFIX . TABLE_SERVICES)
      ->insert(array(
        "id" => null,
        "title" => $this->get("title"),
        "group" => $this->get("group"),
        "icon" => $this->get("icon"),
        "warranty" => $this->get("warranty"),
        "idname" => $this->get("idname"),
        "is_public" => $this->get("is_public"),
        "is_maintaince" => $this->get("is_maintaince"),
        "note" => $this->get("note"),
        "speed" => $this->get("speed"),
        "max_hold" => $this->get("max_hold"),
        "created_at" => $this->get("created_at"),
        "updated_at" => date("Y-m-d H:i:s"),
      ));

    $this->set("id", $id);
    $this->markAsAvailable();
    return $this->get("id");
  }


  /**
   * Update selected entry with Data
   */
  public function update()
  {
    if (!$this->isAvailable())
      return false;

    $this->extendDefaults();

    $id = DB::table(TABLE_PREFIX . TABLE_SERVICES)
      ->where("id", "=", $this->get("id"))
      ->update(array(
        "title" => $this->get("title"),
        "group" => $this->get("group"),
        "icon" => $this->get("icon"),
        "warranty" => $this->get("warranty"),
        "idname" => $this->get("idname"),
        "is_public" => $this->get("is_public"),
        "is_maintaince" => $this->get("is_maintaince"),
        "note" => $this->get("note"),
        "speed" => $this->get("speed"),
        "max_hold" => $this->get("max_hold"),
        "created_at" => $this->get("created_at"),
        "updated_at" => date("Y-m-d H:i:s"),
      ));

    return $this;
  }


  /**
   * Remove selected entry from database
   */
  public function delete()
  {
    if (!$this->isAvailable())
      return false;

    DB::table(TABLE_PREFIX . TABLE_SERVICES)->where("id", "=", $this->get("id"))->delete();
    $this->is_available = false;
    return true;
  }
}
