<?php

/**
 * Role Model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */

class RoleModel extends DataEntry
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
    } else if (filter_var($uniqid, FILTER_VALIDATE_EMAIL)) {
      $col = "idname";
    }

    if ($col) {
      $query = DB::table(TABLE_PREFIX . TABLE_ROLES)
        ->where(TABLE_PREFIX . TABLE_ROLES . "." . $col, "=", $uniqid)
        ->limit(1)
        ->select(TABLE_PREFIX . TABLE_ROLES . ".*");
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
      "idname" => "",
      "site_id" => 0,
      "index_number" => 0,
      "amount" => 0,
      "title" => "",
      "color" => "",
      "created_at" => date("Y-m-d H:i:s"),
      "updated_at" => date("Y-m-d H:i:s"),
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

    $id = DB::table(TABLE_PREFIX . TABLE_ROLES)
      ->insert(array(
        "id" => null,
        "idname" => $this->get("idname"),
        "site_id" => $this->get("site_id"),
        "index_number" => $this->get("index_number"),
        "amount" => $this->get("amount"),
        "title" => $this->get("title"),
        "color" => $this->get("color"),
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

    $id = DB::table(TABLE_PREFIX . TABLE_ROLES)
      ->where("id", "=", $this->get("id"))
      ->update(array(
        "idname" => $this->get("idname"),
        "site_id" => $this->get("site_id"),
        "index_number" => $this->get("index_number"),
        "amount" => $this->get("amount"),
        "title" => $this->get("title"),
        "color" => $this->get("color"),
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

    DB::table(TABLE_PREFIX . TABLE_ROLES)->where("id", "=", $this->get("id"))->delete();
    $this->is_available = false;
    return true;
  }
}
