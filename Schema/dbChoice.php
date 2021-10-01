<?php

require_once require_once dirname(__FILE__) . '/Modifiers.php';

class dbChoice
{
    private $obj = '';

    function __construct($dbType, $dbPath)
    {
        if ($dbType === 'SQLite')
        {
            $this->obj = new Modifiers($dbPath);
        }
    }

    public function listAll()
    {
        $this->obj->listAll();
    }

    public function createEntry($cName, $cModel, $cYear, $cEngine, $cFuel, $isHybrid, $isAWD, $isAutomatic)
    {
        $this->obj->createEntry($cName, $cModel, $cYear, $cEngine, $cFuel, $isHybrid, $isAWD, $isAutomatic);
    }

    public function updateEntry()
    {
        $this->obj->updateEntry();
    }

    public function deleteEntry($cModel, $cYear, $cEngine, $isHybrid, $isAWD, $isAutomatic)
    {
        $this->obj->deleteEntry($cModel, $cYear, $cEngine, $isHybrid, $isAWD, $isAutomatic);
    }
}