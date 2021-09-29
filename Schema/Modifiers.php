<?php

require_once dirname(__FILE__) . '/Database.php';

class Modifiers extends Database
{
    function listAll()
    {
        //TODO Make proper query to list all data.
        $sql = <<<EOF
        SELECT man_name FROM car INNER JOIN ;
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