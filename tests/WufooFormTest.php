<?php

namespace allejo\Wufoo\Tests;

use allejo\Wufoo\EntryQuery;
use allejo\Wufoo\WufooForm;

class WufooFormTest extends \PHPUnit_Framework_TestCase
{
    const FORM_EXAMPLE = 'wufoo-api-example';

    public function testWufooGetAllForms()
    {
        $forms = WufooForm::getForms();

        $this->assertCount(16, $forms);
    }

    public function testWufooGetAllFormsIncludeTodayCount()
    {
        $forms = WufooForm::getForms(true);

        $this->assertCount(16, $forms);
        $this->assertArrayHasKey('EntryCountToday', $forms[0]);
    }

    public function testWufooGetFields()
    {
        $form = new WufooForm(self::FORM_EXAMPLE);
        $fields = $form->getFields();

        $this->assertNotEmpty($fields);

        $foundIsSystem = false;

        foreach ($fields as $field)
        {
            if (isset($field['IsSystem']))
            {
                $foundIsSystem = true;
                break;
            }
        }

        $this->assertFalse($foundIsSystem);
    }

    public function testWufooGetFieldsWithSystem()
    {
        $form = new WufooForm(self::FORM_EXAMPLE);
        $fields = $form->getFields(true);

        $foundIsSystem = false;

        foreach ($fields as $field)
        {
            if (isset($field['IsSystem']))
            {
                $foundIsSystem = true;
                break;
            }
        }

        $this->assertTrue($foundIsSystem);
    }

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

    public function testWufooGetEntriesCount()
    {
        $form = new WufooForm(self::FORM_EXAMPLE);
        $count = $form->getEntriesCount();

        $this->assertGreaterThan(0, $count);
    }

    public function testWufooGetCommentsCount()
    {
        $form = new WufooForm(self::FORM_EXAMPLE);
        $comments = $form->getComments();

        $this->assertCount(3, $comments);
        $this->assertCount($form->getCommentCount(), $comments);
    }

    public function testWufooGetComments()
    {
        $form = new WufooForm(self::FORM_EXAMPLE);
        $commentsEntryEight = $form->getComments(8);

        foreach ($commentsEntryEight as $comment)
        {
            $this->assertEquals(8, $comment['EntryId']);
        }
    }

    public function testWufooGetCommentsPaging()
    {
        $form = new WufooForm(self::FORM_EXAMPLE);
        $firstComment = $form->getComments(8, 0, 1);
        $secondComment = $form->getComments(8, 1);

        $this->assertNotEquals($firstComment, $secondComment);
    }
}
