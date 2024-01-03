<?php

/**
 * Orders model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */
class OrdersModel extends DataList
{
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(DB::table(TABLE_PREFIX . TABLE_ORDERS));
		$this->getQuery();
	}

	public function fetchData()
	{
		$this->getQuery();
		$this->paginate();

		$this->getQuery()
			->select(TABLE_PREFIX . TABLE_ORDERS . ".*");
		$this->data = $this->getQuery()->get();
		return $this;
	}
}
