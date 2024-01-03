<?php

/**
 * Server Model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */

class ServerModel extends DataEntry
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
      $col = null;
    }

    if ($col) {
      $query = DB::table(TABLE_PREFIX . TABLE_SERVERS)
        ->where(TABLE_PREFIX . TABLE_SERVERS . "." . $col, "=", $uniqid)
        ->limit(1)
        ->select(TABLE_PREFIX . TABLE_SERVERS . ".*");
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
      "name" => "",
      "api_url" => "",
      "api_key" => "",
      "is_public" => 1,
      "is_maintaince" => 0,
      "allow_refund" => 1,
      "api_user_id" => '',
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

    $id = DB::table(TABLE_PREFIX . TABLE_SERVERS)
      ->insert(array(
        "id" => null,
        "name" => $this->get("name"),
        "api_url" => $this->get("api_url"),
        "api_key" => $this->get("api_key"),
        "api_user_id" => $this->get("api_user_id"),
        "is_public" => $this->get("is_public"),
        "is_maintaince" => $this->get("is_maintaince"),
        "allow_refund" => $this->get("allow_refund"),
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

    $id = DB::table(TABLE_PREFIX . TABLE_SERVERS)
      ->where("id", "=", $this->get("id"))
      ->update(array(
        "name" => $this->get("name"),
        "api_url" => $this->get("api_url"),
        "api_key" => $this->get("api_key"),
        "api_user_id" => $this->get("api_user_id"),
        "is_public" => $this->get("is_public"),
        "is_maintaince" => $this->get("is_maintaince"),
        "allow_refund" => $this->get("allow_refund"),
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

    DB::table(TABLE_PREFIX . TABLE_SERVERS)->where("id", "=", $this->get("id"))->delete();
    $this->is_available = false;
    return true;
  }
}
