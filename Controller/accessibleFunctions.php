<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/cadata/Schema/dbChoice.php');
require_once(dirname(__FILE__) . '/controllerFunctions.php');

class accessibleFunctions extends controllerFunctions
{

    public function listData()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $responseData = '';
        $keys = $this->getQueryKey();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $db = new dbChoice($this->dbEngine);

                $SQLiteObj = $db->listAll($keys);
                $data = array();
                while ($row = $SQLiteObj->fetchArray(SQLITE3_ASSOC)) {
                    $data[] = $row;
                }
                $responseData = json_encode($data);
            } catch (Error $err) {
                $strErrorDesc = $err->getMessage() . '. Oops, are you sure you\'re making a proper request?';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            $this->sendData(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendData(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function filterData()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $responseData = '';
        $keys = $this->getQueryKey();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $db = new dbChoice($this->dbEngine);

                $SQLiteObj = $db->filter($keys);
                $data = array();
                while ($row = $SQLiteObj->fetchArray(SQLITE3_ASSOC)) {
                    $data[] = $row;
                }
                $responseData = json_encode($data);
            } catch (Error $err) {
                $strErrorDesc = $err->getMessage() . '. Oops, are you sure you\'re making a proper request?';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            $this->sendData(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendData(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function createData()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $responseData = '';

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $db = new dbChoice($this->dbEngine);
                if (count($_POST) !== 8) {
                    throw new Exception('POST expected 8 fields of data');
                }
                if (isset($_POST)) {
                    $db->createEntry($_POST['cName'], $_POST['cModel'], $_POST['cYear'], $_POST['cEngine'], $_POST['cFuel'], $_POST['isHybrid'], $_POST['isAWD'], $_POST['isAutomatic']);
                    print_r($_POST);
                }
            } catch
            (Error $err) {
                $strErrorDesc = $err->getMessage() . '. I don\'t know what you did, but it was deadly.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            $this->sendData(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendData(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function updateData()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $responseData = '';
        $keys = $this->getQueryKey();

        if (strtoupper($requestMethod) == 'PUT') {
            try {
                $_PUT = $this->getFormattedPUT();
                $db = new dbChoice($this->dbEngine);

                if (isset($_PUT)) {
                    echo 'welp';
                    $db->updateEntry($keys, $_PUT);
                }
            } catch
            (Error $err) {
                $strErrorDesc = $err->getMessage() . '. I don\'t know what you did, but it was deadly.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            $this->sendData(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendData(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public
    function deleteData()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $responseData = '';

        if (strtoupper($requestMethod) == 'PUT') {
            try {
                $counter = 0;
                $lines = file('php://input');
                $db = new dbChoice($this->dbEngine);
                foreach ($lines as $i => $line) {
                    $search = 'Content-Disposition: form-data;';
                    if (strpos($line, $search) !== false) {
                        $counter++;
                    }
                    //throw new Exception('Request expected 6 fields of data');
                }
                if ($counter !== 6) {
                    throw new Exception('Request expected 6 fields of data');
                }
                if (isset($lines)) {
                    $db->deleteEntry($this->PUT('cModel'), $this->PUT('cYear'), $this->PUT('cEngine'), $this->PUT('isHybrid'), $this->PUT('isAWD'), $this->PUT('isAutomatic'));
                }
            } catch
            (Error $err) {
                $strErrorDesc = $err->getMessage() . '. I don\'t know what you did, but it was deadly.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            $this->sendData(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendData(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}