<?php

class CreateDatabase extends SQLite3
{
    private $query = '';

    function __construct()
    {
        $this->open('cars.sql');
    }

    function create()
    {
        $query = <<<EOF
        CREATE TABLE sales.stores (
	car_id INT IDENTITY (1, 1) PRIMARY KEY,
	car_name VARCHAR (255),
	car_model VARCHAR (25),
	car_year VARCHAR (255),
	car_insurance VARCHAR (255)
);
EOF;
        $ret = $this->exec($query);
        if (!$ret) echo $this->lastErrorMsg();
        else echo 'Table created successfully';
        $this->close();

    }

    function fill()
    {

    }
}

$make = new CreateDatabase();
$make->create();


