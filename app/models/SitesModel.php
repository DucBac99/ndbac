<?php 
/**
 * Sites model
 *
 * @version 1.0
 * @author ngdanghau <sabosoft.vn>
 * 
 */
class SitesModel extends DataList
{	
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(DB::table(TABLE_PREFIX.TABLE_SITES));
	}
}
