<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/cadata/Schema/Modifiers.php');
require_once(dirname(__FILE__) . '/controllerFunctions.php');

class Accessible extends Controller
{
    /**
     * "/user/list" Endpoint - Get list of users
     */
    public function listData()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $responseData = '';

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $modifiers = new Modifiers($_SERVER['DOCUMENT_ROOT'] . '/cadata/Schema/cars.sql');

                $SQLiteObj = $modifiers->listAll();
                $data = array();
                while ($row = $SQLiteObj->fetchArray(SQLITE3_ASSOC)) {
                    $data[] = $row;
                }
                $responseData = json_encode($data);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
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

    /**
     * @throws Exception
     */
    public function createData()
    {
        $strErrorDesc = '';
        $strErrorHeader = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $responseData = '';

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $modifiers = new Modifiers($_SERVER['DOCUMENT_ROOT'] . '/cadata/Schema/cars.sql');
                if (count($_POST) !== 4) {
                    throw new Exception('POST expected 4 fields of data');
                }
                if (isset($_POST)) {
                    $SQLiteObj = $modifiers->createEntry($_POST['cname'], $_POST['cmodel'], $_POST['cyear'], $_POST['cinsurance']);
                }
            } catch
            (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

// send output
        if (!$strErrorDesc) {
            $this->sendData(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        }
    }
}