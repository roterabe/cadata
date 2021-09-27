<?php

require '../Schema/Modifiers.php';

class Accesible extends Controller
{
    /**
     * "/user/list" Endpoint - Get list of users
     */
    public function listData()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $getQueryAsStringArr = $this->getQueryAsString();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $modifiers = new Modifiers('../Schema/cars.sql');

                $cars = $modifiers->listAll();
                $responseData = json_encode($cars);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}