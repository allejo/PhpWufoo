<?php

namespace allejo\Wufoo\Tests;

use allejo\Wufoo\WufooForm;

class WufooFormTest extends \PHPUnit_Framework_TestCase
{
    public function testWufooGetEntriesWithoutQuery()
    {
        WufooForm::configureApi('fishbowl', 'AOI6-LFKL-VM1Q-IEX9');

        $form = new WufooForm('wufoo-api-example');
        $entries = $form->getEntries();

        $this->assertCount(25, $entries);
    }
}
