<?php

namespace allejo\Wufoo\Tests;

use allejo\Wufoo\EntryFilter;

class EntryFilterTest extends \PHPUnit_Framework_TestCase
{
    public static function dataProviderStringFilters()
    {
        return array(
            array('contains', 'Contains'),
            array('doesNotContain', 'Does_not_contain'),
            array('beginsWith', 'Begins_with'),
            array('endsWith', 'Ends_with'),
            array('lessThan', 'Is_less_than'),
            array('greaterThan', 'Is_greater_than'),
            array('equals', 'Is_equal_to'),
            array('notEqualTo', 'Is_not_equal_to'),
        );
    }

    /**
     * @dataProvider dataProviderStringFilters
     *
     * @param string $fxn
     * @param string $keyword
     */
    public function testStringFilters($fxn, $keyword)
    {
        $field = 'Field1';
        $value = 'containment';

        $f = new EntryFilter($field);
        $f->{$fxn}($value);

        $this->assertEquals(urlencode(sprintf('%s %s %s', $field, $keyword, $value)), (string)$f);
    }

    public static function dataProviderDateFilters()
    {
        $date = new \DateTime('2016-02-02 11:00:00');

        return array(
            array('on', 'Is_on', $date),
            array('before', 'Is_before', $date),
            array('after', 'Is_after', $date),
        );
    }

    /**
     * @dataProvider dataProviderDateFilters
     *
     * @param string    $fxn
     * @param string    $keyword
     * @param \DateTime $date
     */
    public function testDateFilters($fxn, $keyword, $date)
    {
        $field = 'Field1';

        $f = new EntryFilter($field);
        $f->{$fxn}($date);

        $this->assertEquals(
            urlencode(sprintf('%s %s %s', $field, $keyword, $date->format('Y-m-d H:i:s'))),
            (string)$f
        );
    }

    public function testNotNullFilter()
    {
        $field = 'Field1';

        $f = new EntryFilter($field);
        $f->notNull();

        $this->assertEquals(
            urlencode(sprintf('%s Is_not_NULL', $field)), (string)$f
        );
    }
}
