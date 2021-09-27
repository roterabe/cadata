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
        CREATE TABLE cars (
	car_id INTEGER NOT NULL PRIMARY KEY,
	car_name VARCHAR (255),
	car_model VARCHAR (25),
	car_year VARCHAR (255),
	car_insurance BIT (1)
);
EOF;
        $ret = $this->exec($query);
        if (!$ret) echo $this->lastErrorMsg();
        else echo 'Table created successfully <br>';

    }

    function fill()
    {
        $query = <<<EOF
        INSERT INTO cars (car_name, car_model, car_year, car_insurance) VALUES ('Ford', 'Escort', '1999', 1);
        INSERT INTO cars (car_name, car_model, car_year, car_insurance) VALUES ('VW', 'Polo', '1996', 0);
EOF;
        $ret = $this->exec($query);
        if (!$ret) echo $this->lastErrorMsg();
        else echo 'Table filled successfully <br>';

    }
}

$make = new CreateDatabase();
$make->create();
$make->fill();
$make->close();


