<?php

require_once dirname(__FILE__).'/DB_Connect.php';

class Database extends SQLite3 implements DB_Connect
{
    protected $connection = null;

    function __construct($myDB)
    {
        $this->connect($myDB);
    }

    function connect($dir)
    {
        // TODO: Implement connect() method.
        $this->open($dir);

    }
}

