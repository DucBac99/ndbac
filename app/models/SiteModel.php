<?php

/**
 * Site Model
 *
 * @version 1.0
 * @author ngdanghau <sabosoft.vn>
 * 
 */

class SiteModel extends DataEntry
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
    if (is_int($uniqid) || ctype_digit($uniqid)) {
      $col = $uniqid > 0 ? "id" : null;
    } else {
      $col = "domain";
    }

    if ($col) {
      $query = DB::table(TABLE_PREFIX . TABLE_SITES)
        ->where($col, "=", $uniqid)
        ->limit(1)
        ->select("*");
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
      "domain" => "",
      "is_active" => 0,
      "is_root" => 0,
      "settings" => "{}",
      "email_settings" => "{}",
      "banking" => "{}",
      "options" => "{}",
      "theme" => "",
      "bt_id" => 0,
      "zone_id" => "",
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

    $id = DB::table(TABLE_PREFIX . TABLE_SITES)
      ->insert(array(
        "id" => null,
        "domain" => $this->get("domain"),
        "is_active" => $this->get("is_active"),
        "is_root" => $this->get("is_root"),
        "settings" => $this->get("settings"),
        "email_settings" => $this->get("email_settings"),
        "banking" => $this->get("banking"),
        "options" => $this->get("options"),
        "theme" => $this->get("theme"),
        "bt_id" => $this->get("bt_id"),
        "zone_id" => $this->get("zone_id"),
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

    $id = DB::table(TABLE_PREFIX . TABLE_SITES)
      ->where("id", "=", $this->get("id"))
      ->update(array(
        "domain" => $this->get("domain"),
        "is_active" => $this->get("is_active"),
        "is_root" => $this->get("is_root"),
        "settings" => $this->get("settings"),
        "email_settings" => $this->get("email_settings"),
        "banking" => $this->get("banking"),
        "options" => $this->get("options"),
        "theme" => $this->get("theme"),
        "bt_id" => $this->get("bt_id"),
        "zone_id" => $this->get("zone_id"),
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

    DB::table(TABLE_PREFIX . TABLE_SITES)->where("id", "=", $this->get("id"))->delete();
    $this->is_available = false;
    return true;
  }
}
