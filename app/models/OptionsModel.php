<?php 
/**
 * Options model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */
class OptionsModel extends DataList
{	
	/**
	 * Initialize
	 */
	public function __construct()
	{
		$this->setQuery(DB::table(TABLE_PREFIX.TABLE_OPTIONS));
	}
}
