<?php

require_once dirname(__FILE__) . '/Modifiers.php';

class dbChoice
{
    private $db = null;

    function __construct(dbFunctions $db)
    {
        $this->db = new Modifiers($db);
    }

    public function listAll($keys)
    {
        return $this->db->listAll($keys);
    }

    public function filter($keys)
    {
        return $this->db->filter($keys);
    }

    public function createEntry($cName, $cModel, $cYear, $cEngine, $cFuel, $isHybrid, $isAWD, $isAutomatic)
    {
        $this->db->createEntry($cName, $cModel, $cYear, $cEngine, $cFuel, $isHybrid, $isAWD, $isAutomatic);
    }

    public function updateEntry($keys, $_PUT)
    {
        $this->db->updateEntry($keys, $_PUT);
    }

    public function deleteEntry($cModel, $cYear, $cEngine, $isHybrid, $isAWD, $isAutomatic)
    {
        $this->db->deleteEntry($cModel, $cYear, $cEngine, $isHybrid, $isAWD, $isAutomatic);
    }
}