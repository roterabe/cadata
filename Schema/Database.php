<?php

require_once dirname(__FILE__) . '/dbFunctions.php';

class Database extends SQLite3 implements dbFunctions
{
    protected $connection = null;

    function __construct($myDB)
    {
        try {
            $this->connect($myDB);
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    function connect($dir)
    {
        // TODO: Implement connect() method.
        $this->open($dir);

    }
}

