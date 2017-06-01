<?php

namespace allejo\Wufoo\Tests;

use allejo\Wufoo\EntryFilter;
use allejo\Wufoo\EntryQuery;

class EntryQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleFilter()
    {
        $e = new EntryQuery();
        $e->where(
            EntryFilter::create('Field1')->contains('containment')
        );

        $this->assertEquals(
            'Filter1=' . urlencode('Field1 Contains containment'), (string)$e
        );
    }

    public function testMultipleFilters()
    {
        $e = new EntryQuery();
        $e->where([
            EntryFilter::create('Field1')->contains('containment'),
            EntryFilter::create('Field2')->equals('12345'),
        ]);

        $this->assertEquals(
            'Filter1=Field1+Contains+containment&Filter2=Field2+Is_equal_to+12345', (string)$e
        );
    }

    public function testMultipleFiltersBooleanOr()
    {
        $e = new EntryQuery();
        $e->where([
            EntryFilter::create('Field1')->contains('containment'),
            EntryFilter::create('Field2')->equals('12345'),
        ], false);

        $this->assertEquals(
            'Filter1=Field1+Contains+containment&Filter2=Field2+Is_equal_to+12345&match=OR', (string)$e
        );
    }

    public function testPaginateQuery()
    {
        $e = new EntryQuery();
        $e->paginate(5, 10);

        $this->assertEquals(
            'pageStart=5&pageSize=10', (string)$e
        );
    }

    public function testSortByAsc()
    {
        $e = new EntryQuery();
        $e->sortBy('Field1');

        $this->assertEquals('sort=Field1', (string)$e);
    }

    public function testSortByDesc()
    {
        $e = new EntryQuery();
        $e->sortBy('Field1', false);

        $this->assertEquals('sort=Field1&sortDirection=DESC', (string)$e);
    }
}
