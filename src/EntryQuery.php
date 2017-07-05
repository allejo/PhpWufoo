<?php

namespace allejo\Wufoo;

class EntryQuery
{
    private $filters;
    private $booleanAnd;
    private $ascending;
    private $sortField;
    private $pageStart;
    private $pageSize;

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

        // http_build_query() encodes special characters
        return urldecode(http_build_query($query));
    }

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

    public function offset($offset)
    {
        if (!is_int($offset))
        {
            throw new \InvalidArgumentException('$offset must be an integer.');
        }

        $this->pageStart = $offset;

        return $this;
    }

    public function limit($limit)
    {
        if (!is_int($limit))
        {
            throw new \InvalidArgumentException('$limit must be an integer.');
        }

        $this->pageSize = $limit;

        return $this;
    }

    public function paginate($offset, $limit)
    {
        $this->limit($limit);
        $this->offset($offset);

        return $this;
    }

    public function sortBy($field, $ascending = true)
    {
        $this->ascending = $ascending;
        $this->sortField = $field;

        return $this;
    }

    private function setIfNotNull(array &$query, $key, &$checkNull)
    {
        if ($checkNull != null)
        {
            $query[$key] = $checkNull;
        }
    }

    public static function create()
    {
        $query = new self();

        return $query;
    }
}
