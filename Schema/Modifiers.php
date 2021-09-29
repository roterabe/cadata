<?php

require_once dirname(__FILE__) . '/Database.php';

class Modifiers extends Database
{
    function listAll()
    {
        //TODO Make proper query to list all data.
        $sql = <<<EOF
        SELECT manufacturer.man_name as 'Manufacturer', model.m_name as 'Model', model_year.year as 'Year', extras.engine_name as 'Engine', extras.hybrid as 'Hybrid', extras.awd as '4x4', extras.automatic as 'Automatic' FROM car 
        INNER JOIN model on car.m_id = model.m_id 
        INNER JOIN manufacturer on manufacturer.man_id = model.man_id 
        INNER JOIN model_year on model_year.y_id = car.y_id 
        INNER JOIN extras on extras.e_id = car.e_id WHERE is_deleted=0
EOF;
        return $this->query($sql);
    }

    function createEntry($cname, $cmodel, $cyear, $cinsurance)
    {
        $sql = <<<EOF
        INSERT INTO cars (car_name, car_model, car_year, car_insurance) VALUES (?, ?, ?, ?);
EOF;
        $statement = $this->prepare($sql);
        $statement->bindValue(1, $cname, SQLITE3_TEXT);
        $statement->bindValue(2, $cmodel, SQLITE3_TEXT);
        $statement->bindValue(3, $cyear, SQLITE3_INTEGER);
        $statement->bindValue(4, $cinsurance, SQLITE3_INTEGER);
        return $statement->execute();
    }

    function updateEntry()
    {
        $sql = <<<EOF

EOF;

    }
}