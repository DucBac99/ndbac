<?php

/**
 * Services model
 *
 * @version 1.0
 * @author Sabosoft <sabosoft.vn>
 * 
 */
class ServicesModel extends DataList
{
    /**
     * Initialize
     */
    public function __construct()
    {
        $this->setQuery(DB::table(TABLE_PREFIX . TABLE_SERVICES));
    }

    /**
     * Perform a search if $searh_query provided
     * @param  string $search_query 
     * @return self               
     */
    public function search($search_query)
    {
        parent::search($search_query);
        $search_query = $this->getSearchQuery();

        if (!$search_query) {
            return $this;
        }

        $query = $this->getQuery();
        $query->where(TABLE_PREFIX . TABLE_SERVICES . ".title", "LIKE", $search_query . "%");

        return $this;
    }

    public function fetchData()
    {
        $this->paginate();

        $this->getQuery()
            ->select(TABLE_PREFIX . TABLE_SERVICES . ".*");
        $this->data = $this->getQuery()->get();
        return $this;
    }
}