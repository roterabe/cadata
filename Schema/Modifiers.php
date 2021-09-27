<?php

require_once dirname(__FILE__).'/Database.php';

class Modifiers extends Database
{
    function listAll()
    {
        $sql = <<<EOF
        SELECT * from cars;
EOF;
        return $this->query($sql);
    }
}