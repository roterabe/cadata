<?php

require_once dirname(__FILE__) . '/Database.php';

class Modifiers
{
    private $connection;

    function __construct(dbFunctions $conn)
    {
        $this->connection = $conn;
    }

    public function listAll($keys)
    {
        if (count($keys) === 0) {
            $sql = <<<EOF
        SELECT c_id as 'Car ID', manufacturer.man_name as 'Manufacturer', model.m_name as 'Model', model_year.year as 'Year', extras.engine_name as 'Engine', extras.hybrid as 'Hybrid', extras.awd as '4x4', extras.automatic as 'Automatic', car.is_deleted FROM car 
        INNER JOIN model ON car.m_id = model.m_id 
        INNER JOIN manufacturer ON manufacturer.man_id = model.man_id 
        INNER JOIN model_year ON model_year.y_id = car.y_id 
        INNER JOIN extras ON extras.e_id = car.e_id WHERE is_deleted=0
EOF;
            return $this->connection->query($sql);

        } else if (count($keys) === 1) {
            $sql = <<<EOF
        SELECT c_id as 'Car ID', manufacturer.man_name as 'Manufacturer', model.m_name as 'Model', model_year.year as 'Year', extras.engine_name as 'Engine', extras.hybrid as 'Hybrid', extras.awd as '4x4', extras.automatic as 'Automatic', car.is_deleted FROM car 
        INNER JOIN model ON car.m_id = model.m_id 
        INNER JOIN manufacturer ON manufacturer.man_id = model.man_id 
        INNER JOIN model_year ON model_year.y_id = car.y_id 
        INNER JOIN extras ON extras.e_id = car.e_id WHERE is_deleted=0 LIMIT :limit;
EOF;
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':limit', $keys['limit'], SQLITE3_INTEGER);
            return $statement->execute();

        } else if (count($keys) === 2) {
            $sql = <<<EOF
        SELECT c_id as 'Car ID', manufacturer.man_name as 'Manufacturer', model.m_name as 'Model', model_year.year as 'Year', extras.engine_name as 'Engine', extras.hybrid as 'Hybrid', extras.awd as '4x4', extras.automatic as 'Automatic', car.is_deleted FROM car 
        INNER JOIN model ON car.m_id = model.m_id 
        INNER JOIN manufacturer ON manufacturer.man_id = model.man_id 
        INNER JOIN model_year ON model_year.y_id = car.y_id 
        INNER JOIN extras ON extras.e_id = car.e_id WHERE is_deleted=0 LIMIT :limit, :offset;
EOF;
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':limit', $keys['limit'], SQLITE3_INTEGER);
            $statement->bindParam(':offset', $keys['offset'], SQLITE3_INTEGER);
            return $statement->execute();
        }

        return $this->connection->query('SELECT * FROM car;');
    }

    //This function filters by model and orders the data. It should do much more, given the way it's named, but I'll get to that later.
    public function filter($keys)
    {
        $params = ['model', 'year', 'engine'];
        for ($i = 0; $i < count($params); $i++) {
            if (count($keys) === 1 && in_array($params[$i], array_keys($keys))) {
                $sql = <<<EOF
        SELECT c_id as 'Car ID', manufacturer.man_name as 'Manufacturer', model.m_name as 'Model', model_year.year as 'Year', extras.engine_name as 'Engine', extras.hybrid as 'Hybrid', extras.awd as '4x4', extras.automatic as 'Automatic', car.is_deleted FROM car 
        INNER JOIN model ON car.m_id = model.m_id 
        INNER JOIN manufacturer ON manufacturer.man_id = model.man_id 
        INNER JOIN model_year ON model_year.y_id = car.y_id 
        INNER JOIN extras ON extras.e_id = car.e_id WHERE is_deleted=0 AND model.m_name = :m_name;
EOF;
                $statement = $this->connection->prepare($sql);
                $statement->bindParam(':m_name', $keys[$params[$i]], SQLITE3_TEXT);
                return $statement->execute();
            } else if (count($keys) === 3 && in_array($params[$i], array_keys($keys))) {
                if ($keys['order'] === 'descending')
                    $sql = <<<EOF
        SELECT c_id as 'Car ID', manufacturer.man_name as 'Manufacturer', model.m_name as 'Model', model_year.year as 'Year', extras.engine_name as 'Engine', extras.hybrid as 'Hybrid', extras.awd as '4x4', extras.automatic as 'Automatic', car.is_deleted FROM car 
        INNER JOIN model ON car.m_id = model.m_id 
        INNER JOIN manufacturer ON manufacturer.man_id = model.man_id 
        INNER JOIN model_year ON model_year.y_id = car.y_id 
        INNER JOIN extras ON extras.e_id = car.e_id WHERE is_deleted=0 AND model.m_name = :m_name ORDER BY :orderby DESC;
EOF;
                else $sql = <<<EOF
        SELECT c_id as 'Car ID', manufacturer.man_name as 'Manufacturer', model.m_name as 'Model', model_year.year as 'Year', extras.engine_name as 'Engine', extras.hybrid as 'Hybrid', extras.awd as '4x4', extras.automatic as 'Automatic', car.is_deleted FROM car 
        INNER JOIN model ON car.m_id = model.m_id 
        INNER JOIN manufacturer ON manufacturer.man_id = model.man_id 
        INNER JOIN model_year ON model_year.y_id = car.y_id 
        INNER JOIN extras ON extras.e_id = car.e_id WHERE is_deleted=0 AND model.m_name = :m_name ORDER BY :orderby;
EOF;
                $statement = $this->connection->prepare($sql);
                $statement->bindParam(':m_name', $keys[$params[$i]], SQLITE3_TEXT);
                switch ($keys['orderby']) {
                    case 'model':
                        $statement->bindValue(':orderby', 'model.m_name');
                        break;
                    case 'engine':
                        $statement->bindValue(':orderby', 'extras.engine_name');
                        break;
                    case 'year':
                        $statement->bindValue(':orderby', 'model_year.year');
                        break;
                }
                return $statement->execute();
            }
        }
        return -1;
    }

    // Create entry relies in SQLite's UPSERT functionality to insert or update data.
    public function createEntry($cName, $cModel, $cYear, $cEngine, $cFuel, $isHybrid, $isAWD, $isAutomatic)
    {
        echo 'called';
        //TODO make proper queries to insert data.
        $sql = <<<EOF
        INSERT INTO manufacturer (man_name) VALUES (:man_name) ON CONFLICT (man_name) DO UPDATE SET man_name = (:man_name);
EOF;
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':man_name', $cName, SQLITE3_TEXT);
        $res = $statement->execute();
        $res->finalize();
        $sql = <<<EOF
        INSERT INTO model (m_name, man_id) VALUES (:m_name, (SELECT man_id FROM manufacturer WHERE man_name=:man_name)) ON CONFLICT (m_name) DO UPDATE SET m_name = (:m_name);
EOF;
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':m_name', $cModel, SQLITE3_TEXT);
        $statement->bindParam(':man_name', $cName, SQLITE3_TEXT);
        $res = $statement->execute();
        $res->finalize();
        $sql = <<<EOF
        INSERT INTO model_year (year) VALUES (:cYear) ON CONFLICT (year) DO UPDATE SET year = (:cYear);
EOF;
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':cYear', $cYear, SQLITE3_INTEGER);
        $res = $statement->execute();
        $res->finalize();
        $sql = <<<EOF
        INSERT INTO engine (engine_name, fuel) VALUES (:engine_name, :fuel) ON CONFLICT (engine_name, fuel) DO UPDATE SET engine_name = (:engine_name), fuel = (:fuel);
EOF;
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':engine_name', $cEngine, SQLITE3_TEXT);
        $statement->bindParam(':fuel', $cFuel, SQLITE3_TEXT);
        $res = $statement->execute();
        $res->finalize();
        $sql = <<<EOF
        INSERT INTO extras (engine_name, hybrid, awd, automatic) VALUES (:engine_name, :isHybrid, :isAWD, :isAutomatic) ON CONFLICT (engine_name, hybrid, awd, automatic) DO UPDATE SET engine_name = (:engine_name), hybrid = (:isHybrid), awd = (:isAWD), automatic = (:isAutomatic);
EOF;
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':engine_name', $cEngine, SQLITE3_TEXT);
        $statement->bindParam(':isHybrid', $isHybrid, SQLITE3_INTEGER);
        $statement->bindParam(':isAWD', $isAWD, SQLITE3_INTEGER);
        $statement->bindParam(':isAutomatic', $isAutomatic, SQLITE3_INTEGER);
        $res = $statement->execute();
        $res->finalize();

        $sql = <<<EOF
        INSERT INTO car (m_id, y_id, e_id, is_deleted) VALUES ((SELECT m_id FROM model WHERE m_name = :m_name), (SELECT y_id FROM model_year WHERE year = :cYear),
        (SELECT e_id FROM extras WHERE engine_name = :engine_name AND hybrid = :isHybrid AND awd = :isAWD AND automatic = :isAutomatic), 0)
        ON CONFLICT (m_id, y_id, e_id) DO UPDATE SET m_id = (SELECT m_id FROM model WHERE m_name = :m_name), y_id = (SELECT y_id FROM model_year WHERE year = :cYear),
        e_id = (SELECT e_id FROM extras WHERE engine_name = :engine_name AND hybrid = :isHybrid AND awd = :isAWD AND automatic = :isAutomatic), is_deleted = 0;
EOF;
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':m_name', $cModel, SQLITE3_TEXT);
        $statement->bindParam(':cYear', $cYear, SQLITE3_INTEGER);
        $statement->bindParam(':engine_name', $cEngine, SQLITE3_TEXT);
        $statement->bindParam(':isHybrid', $isHybrid, SQLITE3_INTEGER);
        $statement->bindParam(':isAWD', $isAWD, SQLITE3_INTEGER);
        $statement->bindParam(':isAutomatic', $isAutomatic, SQLITE3_INTEGER);
        $res = $statement->execute();
        $res->finalize();

    }

    public
    function updateEntry($keys, $_PUT)
    {
        $id = $keys['cID'];
        print_r($_PUT);
        for ($i = 0; $i < count($_PUT); $i++) {
            switch (array_keys($_PUT)[$i]) {
                case 'cModel':
                    $sql = <<<EOF
                INSERT INTO manufacturer (man_name) VALUES (:man_name) ON CONFLICT (man_name) DO UPDATE SET man_name = (:man_name);
EOF;
                    $statement = $this->connection->prepare($sql);
                    $statement->bindParam(':man_name', $_PUT['cName'], SQLITE3_TEXT);
                    $res = $statement->execute();
                    $res->finalize();
                    $sql = <<<EOF
                INSERT INTO model (m_name, man_id) VALUES (:m_name, (SELECT man_id FROM manufacturer WHERE man_name=:man_name)) ON CONFLICT (m_name) DO UPDATE SET m_name = (:m_name);
EOF;
                    $statement = $this->connection->prepare($sql);
                    $statement->bindParam(':m_name', $_PUT['cModel'], SQLITE3_TEXT);
                    $statement->bindParam(':man_name', $_PUT['cName'], SQLITE3_TEXT);
                    $res = $statement->execute();
                    $res->finalize();
                    //TODO first push data from back and then move over to 'car' table to update. This is done so as to be sure that you have something to update with.
                    $sql = <<<EOF
                UPDATE car SET m_id = (SELECT m_id FROM model WHERE m_name = :m_name) WHERE c_id = :c_id;
EOF;
                    $statement = $this->connection->prepare($sql);
                    $statement->bindParam(':m_name', $_PUT['cModel'], SQLITE3_TEXT);
                    $statement->bindParam(':c_id', $id, SQLITE3_INTEGER);
                    $res = $statement->execute();
                    $res->finalize();
                    break;

                case 'cYear':
                    $sql = <<<EOF
                INSERT INTO model_year (year) VALUES (:cYear) ON CONFLICT (year) DO UPDATE SET year = (:cYear);
EOF;
                    $statement = $this->connection->prepare($sql);
                    $statement->bindParam(':cYear', $_PUT['cYear'], SQLITE3_INTEGER);
                    $res = $statement->execute();
                    $res->finalize();
                    $sql = <<<EOF
                UPDATE car SET y_id = (SELECT y_id FROM model_year WHERE year = :cYear) WHERE c_id = :c_id;
EOF;
                    $statement = $this->connection->prepare($sql);
                    $statement->bindParam(':cYear', $_PUT['cYear'], SQLITE3_INTEGER);
                    $statement->bindParam(':c_id', $id, SQLITE3_INTEGER);
                    $res = $statement->execute();
                    $res->finalize();
                    break;

                case 'cEngine':
                    $sql = <<<EOF
                    INSERT INTO engine (engine_name, fuel) VALUES (:engine_name, :fuel) ON CONFLICT (engine_name, fuel) DO UPDATE SET engine_name = (:engine_name), fuel = (:fuel);
EOF;
                    $statement = $this->connection->prepare($sql);
                    $statement->bindParam(':engine_name', $_PUT['cEngine'], SQLITE3_TEXT);
                    $statement->bindParam(':fuel', $_PUT['cFuel'], SQLITE3_TEXT);
                    $res = $statement->execute();
                    $res->finalize();
                    $sql = <<<EOF
                    INSERT INTO extras (engine_name, hybrid, awd, automatic) VALUES (:engine_name, :isHybrid, :isAWD, :isAutomatic) ON CONFLICT (engine_name, hybrid, awd, automatic) DO UPDATE SET engine_name = (:engine_name), hybrid = (:isHybrid), awd = (:isAWD), automatic = (:isAutomatic);
EOF;
                    $statement = $this->connection->prepare($sql);
                    $statement->bindParam(':engine_name', $cEngine, SQLITE3_TEXT);
                    $statement->bindParam(':isHybrid', $isHybrid, SQLITE3_INTEGER);
                    $statement->bindParam(':isAWD', $isAWD, SQLITE3_INTEGER);
                    $statement->bindParam(':isAutomatic', $isAutomatic, SQLITE3_INTEGER);
                    $res = $statement->execute();
                    $res->finalize();
                    $sql = <<<EOF
                    UPDATE car SET e_id = (SELECT e_id FROM extras WHERE engine_name = :engine_name AND hybrid = :isHybrid AND awd = :isAWD AND automatic = :isAutomatic) WHERE c_id = :c_id;
EOF;
                    $statement = $this->connection->prepare($sql);
                    $statement->bindParam(':engine_name', $cEngine, SQLITE3_TEXT);
                    $statement->bindParam(':isHybrid', $isHybrid, SQLITE3_INTEGER);
                    $statement->bindParam(':isAWD', $isAWD, SQLITE3_INTEGER);
                    $statement->bindParam(':isAutomatic', $isAutomatic, SQLITE3_INTEGER);
                    $statement->bindParam(':c_id', $id, SQLITE3_INTEGER);
                    $res = $statement->execute();
                    $res->finalize();

                    break;
            }
        }

        /*$sql = <<<EOF
    UPDATE extras SET engine_name = :engine_name
    EOF;
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':engine_name', $cEngine);
        $res = $statement->execute();
        $res->finalize();*/

    }

    public
    function deleteEntry($cModel, $cYear, $cEngine, $isHybrid, $isAWD, $isAutomatic)
    {
        $sql = <<<EOF
        UPDATE car SET is_deleted = 1 WHERE m_id = (SELECT m_id FROM model WHERE m_name = :m_name) AND y_id = (SELECT y_id FROM model_year WHERE year = :cYear)
        AND e_id = (SELECT e_id FROM extras WHERE engine_name = :engine_name AND hybrid = :isHybrid AND awd = :isAWD AND automatic = :isAutomatic);
EOF;
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':m_name', $cModel, SQLITE3_TEXT);
        $statement->bindParam(':cYear', $cYear, SQLITE3_INTEGER);
        $statement->bindParam(':engine_name', $cEngine, SQLITE3_TEXT);
        $statement->bindParam(':isHybrid', $isHybrid, SQLITE3_INTEGER);
        $statement->bindParam(':isAWD', $isAWD, SQLITE3_INTEGER);
        $statement->bindParam(':isAutomatic', $isAutomatic, SQLITE3_INTEGER);
        $res = $statement->execute();
        $res->finalize();

    }
}