<?php

/**
 * Themes model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */
class ThemesModel extends DataList
{
  /**
   * Initialize
   */
  public function __construct()
  {
    $this->setQuery(DB::table(TABLE_PREFIX . TABLE_THEMES));
  }

  public function fetchData()
  {
    $this->getQuery();
    $this->paginate();

    $this->getQuery()
      ->select(TABLE_PREFIX . TABLE_THEMES . ".*");
    $this->data = $this->getQuery()->get();
    return $this;
  }
}
