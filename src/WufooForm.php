<?php

namespace allejo\Wufoo;

class WufooForm
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getEntries(EntryQuery $query = null)
    {

    }
}
