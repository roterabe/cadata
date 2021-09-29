<?php

require_once dirname(__FILE__) . '/Database.php';

class Modifiers extends Database
{
    function listAll()
    {
        //TODO Make proper query to list all data.
        $sql = <<<EOF
        SELECT manufacturer.man_name as 'Manufacturer', model.m_name as 'Model', model_year.year as 'Year', extras.engine_name as 'Engine', extras.hybrid as 'Hybrid', extras.awd as '4x4', extras.automatic as 'Automatic' FROM car 
        INNER JOIN model ON car.m_id = model.m_id 
        INNER JOIN manufacturer ON manufacturer.man_id = model.man_id 
        INNER JOIN model_year ON model_year.y_id = car.y_id 
        INNER JOIN extras ON extras.e_id = car.e_id WHERE is_deleted=0
EOF;
        return $this->query($sql);
    }

    function createEntry($cName, $cModel, $cYear, $cEngine, $cFuel, $isHybrid, $isAWD, $isAutomatic)
    {
        //TODO make proper queries to insert data.
        $sql = <<<EOF
        INSERT INTO manufacturer (man_name) VALUES (:man_name) ON CONFLICT (man_name) DO UPDATE SET man_name = (:man_name);
        INSERT INTO model (m_name) VALUES (:m_name) ON CONFLICT (m_name) DO UPDATE SET m_name = (:m_name);
        INSERT INTO model_year (year) VALUES (:cYear) ON CONFLICT (year) DO UPDATE SET year = (:cYear);
        INSERT INTO engine (engine_name, fuel) VALUES (:engine_name, :fuel) ON CONFLICT (engine_name) DO UPDATE SET engine_name = (:engine_name), fuel = (:fuel);
        INSERT INTO extras (engine_name, hybrid, awd, automatic) VALUES (:engine_name, :isHybrid, :isAWD, :isAutomatic) ON CONFLICT (engine_name, hybrid, awd, automatic) DO UPDATE SET engine_name = (:engine_name), hybrid = (:isHybrid), awd = (:isAWD), automatic = (:isAutomatic);
        INSERT INTO car (m_id, y_id, e_id) VALUES ((SELECT m_id FROM model WHERE m_name = :man_name), (SELECT y_id FROM mode_year WHERE year = :cYear), 
        (SELECT e_id FROM extras WHERE engine_name = :engine_name AND hybrid = :isHybrid AND awd = :isAWD AND automatic = :isAutomatic), 0)
        ON CONFLICT DO UPDATE SET m_id = (SELECT m_id FROM model WHERE m_name = :man_name), y_id = (SELECT y_id FROM mode_year WHERE year = :cYear),
        e_id = (SELECT e_id FROM extras WHERE engine_name = :engine_name AND hybrid = :isHybrid AND awd = :isAWD AND automatic = :isAutomatic), isDeleted = 0;
EOF;
        $statement = $this->prepare($sql);
        $statement->bindParam(':man_name', $cName, SQLITE3_TEXT);
        $statement->bindParam(':m_name', $cModel, SQLITE3_TEXT);
        $statement->bindParam(':cYear', $cYear, SQLITE3_INTEGER);
        $statement->bindParam(':engine_name', $cEngine, SQLITE3_TEXT);
        $statement->bindParam(':fuel', $cFuel, SQLITE3_TEXT);
        $statement->bindParam(':isHybrid', $isHybrid, SQLITE3_INTEGER);
        $statement->bindParam(':isAWD', $isAWD, SQLITE3_INTEGER);
        $statement->bindParam(':isAutomatic', $isAutomatic, SQLITE3_INTEGER);
        return $statement->execute();
    }

    function updateEntry()
    {
        $sql = <<<EOF

EOF;

    }
}