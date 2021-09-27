<?php

requre_once . "Database.php";

class Modifiers extends Database
{
    function listAll()
    {
        $sql = <<<EOF
        SELECT * from CARS;
EOF;

        return $this->query($sql);
    }
}