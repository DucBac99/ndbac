<?php

/**
 * VipOrder Model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */

class VipOrderModel extends DataEntry
{
  /**
   * Extend parents constructor and select entry
   * @param mixed $uniqid Value of the unique identifier
   */
  public function __construct(...$uniqid)
  {
    parent::__construct();
    $this->select($uniqid);
  }

  /**
   * Select entry with uniqid
   * @param  int|string|array $uniqid Value of the any unique field
   * @return self       
   */
  public function select($uniqid)
  {
    if (is_array($uniqid) && count($uniqid) == 1) {
      $uniqid = $uniqid[0];
    }

    if (is_int($uniqid) || ctype_digit($uniqid)) {
      $col = $uniqid > 0 ? "id" : null;
    } else if (is_array($uniqid) && count($uniqid) == 2) {
      $col = $uniqid[0];
      $uniqid = $uniqid[1];
    } else {
      $col = null;
    }

    if ($col) {
      $query = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
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
      'user_id' => 0,
      'source_id' => "",
      'source_from' => 0,
      'seeding_uid' => "",
      'order_amount' => 0,
      'real_amount' => 0,
      'price' => 0,
      'priceAdmin' => 0,
      'month' => 0,
      'seeding_type' => "",
      'reaction_type' => "",
      'comment_need' => "",
      'status' => "",
      'wwwURL' => "",
      '__typename' => "",
      'note' => "",
      'note_extra' => "",
      'expired_at' => date("Y-m-d H:i:s"),
      'created_at' => date("Y-m-d H:i:s"),
      'updated_at' => date("Y-m-d H:i:s"),
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

    $id = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
      ->insert(array(
        "id" => null,
        'user_id' => $this->get("user_id"),
        'source_id' => $this->get("source_id"),
        'source_from' => $this->get("source_from"),
        'seeding_uid' => $this->get("seeding_uid"),
        'month' => $this->get("month"),
        'order_amount' => $this->get("order_amount"),
        'real_amount' => $this->get("real_amount"),
        'price' => $this->get("price"),
        'priceAdmin' => $this->get("priceAdmin"),
        'seeding_type' => $this->get("seeding_type"),
        'reaction_type' => $this->get("reaction_type"),
        'comment_need' => $this->get("comment_need"),
        'status' => $this->get("status"),
        'wwwURL' => $this->get("wwwURL"),
        '__typename' => $this->get("__typename"),
        'note' => $this->get("note"),
        'note_extra' => $this->get("note_extra"),
        'expired_at' => $this->get("expired_at"),
        'created_at' => $this->get("created_at"),
        'updated_at' => date("Y-m-d H:i:s"),
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

    $id = DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)
      ->where("id", "=", $this->get("id"))
      ->update(array(
        'user_id' => $this->get("user_id"),
        'source_id' => $this->get("source_id"),
        'source_from' => $this->get("source_from"),
        'seeding_uid' => $this->get("seeding_uid"),
        'month' => $this->get("month"),
        'order_amount' => $this->get("order_amount"),
        'price' => $this->get("price"),
        'priceAdmin' => $this->get("priceAdmin"),
        'seeding_type' => $this->get("seeding_type"),
        'reaction_type' => $this->get("reaction_type"),
        'comment_need' => $this->get("comment_need"),
        'status' => $this->get("status"),
        'wwwURL' => $this->get("wwwURL"),
        '__typename' => $this->get("__typename"),
        'note' => $this->get("note"),
        'note_extra' => $this->get("note_extra"),
        'expired_at' => $this->get("expired_at"),
        'created_at' => $this->get("created_at"),
        'updated_at' => date("Y-m-d H:i:s"),
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

    DB::table(TABLE_PREFIX . TABLE_VIP_ORDERS)->where("id", "=", $this->get("id"))->delete();
    $this->is_available = false;
    return true;
  }
}
