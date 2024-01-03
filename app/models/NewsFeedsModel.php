<?php

/**
 * NewsFeeds model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */
class NewsFeedsModel extends DataList
{
  /**
   * Initialize
   */
  public function __construct()
  {
    $this->setQuery(DB::table(TABLE_PREFIX . TABLE_NEWFEEDS));
    $this->getQuery()
      ->leftJoin(
        TABLE_PREFIX . TABLE_USERS,
        TABLE_PREFIX . TABLE_NEWFEEDS . '.user_id',
        '=',
        TABLE_PREFIX . TABLE_USERS . '.id'
      );
  }

  public function fetchData()
  {
    $this->getQuery();
    $this->paginate();

    $this->getQuery()
      ->select(TABLE_PREFIX . TABLE_NEWFEEDS . ".*")
      ->select(TABLE_PREFIX . TABLE_USERS . ".firstname", TABLE_PREFIX . TABLE_USERS . ".lastname");
    $this->data = $this->getQuery()->get();
    return $this;
  }
}
