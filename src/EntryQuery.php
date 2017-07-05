<?php

namespace allejo\Wufoo;

/**
 * An EntryQuery is used to specify how the results will be fetched and what entry filters will be used.
 *
 * @api
 * @since 0.1.0
 */
class EntryQuery
{
    private $filters;
    private $booleanAnd;
    private $ascending;
    private $sortField;
    private $pageStart;
    private $pageSize;
    private $system;

    /**
     * @api
     *
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->filters = [];
        $this->ascending = true;
    }

    public function __toString()
    {
        $query = [];

        $this->setIfNotNull($query, 'sort', $this->sortField);

        // 'ASC' is the default sorting option, so only set descending order if needed
        if (!$this->ascending)
        {
            $query['sortDirection'] = 'DESC';
        }

        $this->setIfNotNull($query, 'pageStart', $this->pageStart);
        $this->setIfNotNull($query, 'pageSize', $this->pageSize);

        $filterCounter = 1;

        foreach ($this->filters as $filter)
        {
            $query[sprintf('Filter%d', $filterCounter)] = (string)$filter;
            $filterCounter++;
        }

        // 'AND' is the default sorting option, so only explicitly set it when it's an OR
        if (!empty($this->filters) && !$this->booleanAnd)
        {
            $query['match'] = 'OR';
        }

        $this->setIfNotNull($query, 'system', $this->system);

        // http_build_query() encodes special characters
        return urldecode(http_build_query($query));
    }

    /**
     * Filter the results based on entry filters.
     *
     * @api
     *
     * @param EntryFilter|EntryFilter[] $filters
     * @param bool                      $booleanAnd
     *
     * @since 0.1.0
     *
     * @return $this
     */
    public function where($filters, $booleanAnd = true)
    {
        $this->booleanAnd = (bool)$booleanAnd;

        if (!is_array($filters))
        {
            array_push($this->filters, $filters);
            return $this;
        }

        foreach ($filters as $filter)
        {
            array_push($this->filters, $filter);
        }

        return $this;
    }

    /**
     * When paginating results of a query, set the offset.
     *
     * **Warning:** This function should _not_ be used in conjunction with EntryQuery::paginate().
     *
     * @api
     *
     * @param int $offset
     *
     * @throws \InvalidArgumentException if $offset is not an integer
     *
     * @since 0.1.0
     *
     * @return $this
     */
    public function offset($offset)
    {
        if (!is_int($offset))
        {
            throw new \InvalidArgumentException('$offset must be an integer.');
        }

        $this->pageStart = $offset;

        return $this;
    }

    /**
     * Set the number of results to be returned in a query.
     *
     * **Warnings**
     *
     * - The API restricts this value to a maximum of 100. However, this function will not impose any restrictions should
     *   the API change this restriction
     * - This function should _not_ bt used in conjunction with EntryQuery::paginate()
     *
     * @api
     *
     * @param int $limit
     *
     * @throws \InvalidArgumentException if $limit is not an integer
     *
     * @since 0.1.0
     *
     * @return $this
     */
    public function limit($limit)
    {
        if (!is_int($limit))
        {
            throw new \InvalidArgumentException('$limit must be an integer.');
        }

        $this->pageSize = $limit;

        return $this;
    }

    /**
     * Define the pagination for this query.
     *
     * **Warning:** This function is provided as a convenience function that will set both the offset and limit in one
     * function call. This should be instead of calling EntryQuery::offset() and EntryQuery::limit() separately.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @throws \InvalidArgumentException if $offset or $limit are not integers
     *
     * @since 0.1.0
     *
     * @return $this
     */
    public function paginate($offset, $limit)
    {
        $this->limit($limit);
        $this->offset($offset);

        return $this;
    }

    /**
     * Sort the results based on a field.
     *
     * @api
     *
     * @param string $field     The API Field ID to sort by
     * @param bool   $ascending Set to true to sort in ascending order
     *
     * @since 0.1.0
     *
     * @return $this
     */
    public function sortBy($field, $ascending = true)
    {
        $this->ascending = $ascending;
        $this->sortField = $field;

        return $this;
    }

    /**
     * Whether or not to receive system fields for the entries.
     *
     * @api
     *
     * @param bool $system
     *
     * @since 0.1.0
     *
     * @return $this
     */
    public function getSystemFields($system = true)
    {
        // Per the API documentation, if 'system' is set to _anything_, then it'll return these fields. This is why we
        // need to explicitly check for a true value
        if ($system === true)
        {
            $this->system = 'true';
        }

        return $this;
    }

    private function setIfNotNull(array &$query, $key, &$checkNull)
    {
        if ($checkNull != null)
        {
            $query[$key] = $checkNull;
        }
    }

    /**
     * Convenience function to create an EntryQuery that be used for immediate chaining.
     *
     * @return EntryQuery
     */
    public static function create()
    {
        $query = new self();

        return $query;
    }
}
