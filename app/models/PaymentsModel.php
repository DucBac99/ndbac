<?php

/**
 * Payments model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */
class PaymentsModel extends DataList
{
  /**
   * Initialize
   */
  public function __construct()
  {
    $this->setQuery(DB::table(TABLE_PREFIX . TABLE_PAYMENTS));
  }

  public function fetchData()
  {
    $this->getQuery()
      ->leftJoin(
        TABLE_PREFIX . TABLE_USERS,
        TABLE_PREFIX . TABLE_PAYMENTS . ".user_id",
        "=",
        TABLE_PREFIX . TABLE_USERS . ".id"
      );
    $this->paginate();

    $this->getQuery()
      ->select(TABLE_PREFIX . TABLE_PAYMENTS . ".*")
      ->select([TABLE_PREFIX . TABLE_USERS . ".email", TABLE_PREFIX . TABLE_USERS . ".firstname", TABLE_PREFIX . TABLE_USERS . ".lastname"]);
    $this->data = $this->getQuery()->get();
    return $this;
  }
}
