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
        CREATE TABLE IF NOT EXISTS manufacturer (
	man_id INTEGER NOT NULL PRIMARY KEY,
	man_name VARCHAR(255) UNIQUE NOT NULL
);
        CREATE TABLE IF NOT EXISTS model (
    m_id INTEGER NOT NULL PRIMARY KEY,
    man_id INTEGER NOT NULL,
    m_name VARCHAR(255) UNIQUE NOT NULL,
    CONSTRAINT man_fk FOREIGN KEY (man_id) REFERENCES manufacturer(man_id)     
);
        CREATE TABLE IF NOT EXISTS model_year (
    y_id INTEGER NOT NULL PRIMARY KEY,
    year INTEGER UNIQUE NOT NULL         
);
        CREATE TABLE IF NOT EXISTS engine (
    engine_name VARCHAR(255) NOT NULL PRIMARY KEY,
    fuel VARCHAR(255) NOT NULL          
);
        CREATE TABLE IF NOT EXISTS extras (
    e_id INTEGER NOT NULL PRIMARY KEY,
    engine_name VARCHAR(255) NOT NULL,
    hybrid INTEGER NOT NULL,
    awd INTEGER NOT NULL,
    automatic INTEGER NOT NULL,
    CONSTRAINT engine_fk FOREIGN KEY (engine_name) REFERENCES engine(engine_name)    
);
        CREATE TABLE IF NOT EXISTS car (
    c_id INTEGER NOT NULL PRIMARY KEY,
    m_id INTEGER NOT NULL,
    y_id INTEGER NOT NULL,
    e_id INTEGER NOT NULL,
    is_deleted INTEGER NOT NULL, 
    CONSTRAINT m_fk FOREIGN KEY (m_id) REFERENCES model(m_id),
    CONSTRAINT y_fk FOREIGN KEY (y_id) REFERENCES model_year(y_id),
    CONSTRAINT e_fk FOREIGN KEY (e_id) REFERENCES extras(e_id)   
);
    
EOF;
        $ret = $this->exec($query);
        if (!$ret) echo $this->lastErrorMsg();
        else echo 'Table created successfully <br>';

    }

    function fill()
    {
        $query = <<<EOF
    INSERT INTO manufacturer (man_name) VALUES ('Ford');
    INSERT INTO manufacturer (man_name) VALUES ('Volkswagen');
    INSERT INTO manufacturer (man_name) VALUES ('BMW');
    INSERT INTO manufacturer (man_name) VALUES ('Audi');
    INSERT INTO manufacturer (man_name) VALUES ('Mercedes');
    
    INSERT INTO model (man_id, m_name) VALUES ((SELECT man_id FROM manufacturer WHERE man_name='Ford'), 'Escort');
    INSERT INTO model (man_id, m_name) VALUES ((SELECT man_id FROM manufacturer WHERE man_name='BMW'), 'E30');
    INSERT INTO model (man_id, m_name) VALUES ((SELECT man_id FROM manufacturer WHERE man_name='Audi'), 'A4');
    INSERT INTO model (man_id, m_name) VALUES ((SELECT man_id FROM manufacturer WHERE man_name='Volkswagen'), 'Polo');

    INSERT INTO model_year (year) VALUES (1990);
    INSERT INTO model_year (year) VALUES (2000);
    INSERT INTO model_year (year) VALUES (1996);
    INSERT INTO model_year (year) VALUES (1997);

    INSERT INTO engine (engine_name, fuel) VALUES ('1.4', 'Petrol');
    INSERT INTO engine (engine_name, fuel) VALUES ('1.8T', 'Petrol');
    INSERT INTO engine (engine_name, fuel) VALUES ('1.6D', 'Diesel');
    INSERT INTO engine (engine_name, fuel) VALUES ('2.0', 'Petrol');

    INSERT INTO extras (engine_name, hybrid, awd, automatic) VALUES ((SELECT engine_name FROM engine WHERE engine_name='1.4'), 0, 0, 0);
    INSERT INTO extras (engine_name, hybrid, awd, automatic) VALUES ((SELECT engine_name FROM engine WHERE engine_name='1.8T'), 0, 1, 0);
    INSERT INTO extras (engine_name, hybrid, awd, automatic) VALUES ((SELECT engine_name FROM engine WHERE engine_name='1.6D'), 0, 0, 1);
    INSERT INTO extras (engine_name, hybrid, awd, automatic) VALUES ((SELECT engine_name FROM engine WHERE engine_name='2.0'), 0, 0, 0);

    INSERT INTO car (m_id, y_id, e_id, is_deleted) VALUES ((SELECT m_id FROM model WHERE m_name='Escort'),(SELECT y_id FROM model_year WHERE year=1997),(SELECT e_id FROM extras WHERE engine_name='1.6D'), 0);
INSERT INTO car (m_id, y_id, e_id, is_deleted) VALUES ((SELECT m_id FROM model WHERE m_name='Polo'),(SELECT y_id FROM model_year WHERE year=1996),(SELECT e_id FROM extras WHERE engine_name='1.4'), 0);
INSERT INTO car (m_id, y_id, e_id, is_deleted) VALUES ((SELECT m_id FROM model WHERE m_name='A4'),(SELECT y_id FROM model_year WHERE year=2000),(SELECT e_id FROM extras WHERE engine_name='1.8T'), 0);
INSERT INTO car (m_id, y_id, e_id, is_deleted) VALUES ((SELECT m_id FROM model WHERE m_name='E30'),(SELECT y_id FROM model_year WHERE year=1990),(SELECT e_id FROM extras WHERE engine_name='2.0'), 0);
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


