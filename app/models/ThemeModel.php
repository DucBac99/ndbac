<?php

/**
 * Theme Model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */

class ThemeModel extends DataEntry
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
      $col = "idname";
    }

    if ($col) {
      $query = DB::table(TABLE_PREFIX . TABLE_THEMES)
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
      "idname" => "",
      "thumb" => "",
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

    $id = DB::table(TABLE_PREFIX . TABLE_THEMES)
      ->insert(array(
        "id" => null,
        "idname" => $this->get("idname"),
        "thumb" => $this->get("thumb"),
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

    $id = DB::table(TABLE_PREFIX . TABLE_THEMES)
      ->where("id", "=", $this->get("id"))
      ->update(array(
        "idname" => $this->get("idname"),
        "thumb" => $this->get("thumb"),
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

    DB::table(TABLE_PREFIX . TABLE_THEMES)->where("id", "=", $this->get("id"))->delete();
    $this->is_available = false;
    return true;
  }
}
