<?php

namespace allejo\Wufoo\Tests;

use allejo\Wufoo\EntryQuery;
use allejo\Wufoo\WufooForm;

class WufooFormTest extends \PHPUnit_Framework_TestCase
{
    const FORM_EXAMPLE = 'wufoo-api-example';

    public function testWufooGetEntriesWithoutQuery()
    {
        $form = new WufooForm(self::FORM_EXAMPLE);
        $entries = $form->getEntries();

        $this->assertCount(25, $entries);
    }

    public function testWufooGetEntriesWithQuery()
    {
        $limit = 5;

        $query = EntryQuery::create()
            ->limit($limit)
        ;

        $form = new WufooForm(self::FORM_EXAMPLE);
        $entries = $form->getEntries($query);

        $this->assertCount($limit, $entries);
    }

    public function testWufooGetEntriesSorted()
    {
        $query = EntryQuery::create()
            ->sortBy('EntryId', false)
            ->limit(10)
        ;

        $form = new WufooForm(self::FORM_EXAMPLE);
        $entries = $form->getEntries($query);

        $this->assertGreaterThan($entries[9]['EntryId'], $entries[0]['EntryId']);
    }
}
