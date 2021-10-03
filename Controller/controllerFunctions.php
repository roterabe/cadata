<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/cadata/Schema/dbFunctions.php');

class controllerFunctions
{
    protected $dbEngine = null;

    function __construct(dbFunctions $dbEngine)
    {
        $this->dbEngine = $dbEngine;
    }

    public function __call($name, $arguments)
    {
        $this->sendData('', array('HTTP/1.1 404 Not Found'));
    }

/*    protected function PUT($key)
    {
        $inputFileSrc = 'php://input';
        $lines = file($inputFileSrc);

        foreach ($lines as $i => $line) {
            $search = 'Content-Disposition: form-data; name="' . $key . '"';
            if (strpos($line, $search) !== false) {
                return trim($lines[$i + 2]);
            }
        }
        return -1;
    }*/

    protected function getFormattedPUT(): array
    {
        $inputFileSrc = 'php://input';
        $lines = file($inputFileSrc);
        $data = array();
        foreach ($lines as $i => $line) {
            $mod = '';
            preg_match('/(c|is)[[:upper:]]\S*\b/', $line, $mod);
            foreach ($mod as $key) {
                $search = 'Content-Disposition: form-data; name="' . $key . '"';
                if (strpos($line, $search) !== false) {
                    $data[$key] = trim($lines[$i + 2]);
                }
            }
        }
        return $data;
    }

    protected function getQueryKey(): array
    {
        $query = array();
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }

    protected function sendData($data, $headers = array())
    {
        header_remove('Set-Cookie');

        if (is_array($headers) && count($headers)) {
            foreach ($headers as $httpHeader) {
                header($httpHeader);
            }
        }
        echo $data;
        exit;
    }
}