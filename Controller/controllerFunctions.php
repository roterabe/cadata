<?php

class Controller
{
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $this->sendData('', array('HTTP/1.1 404 Not Found'));
    }

    protected function parseURI()
    {
        $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        return $uriSegments;
    }

    protected function sendData($data, $headers=array())
    {
        header_remove('Set-Cookie');

        if (is_array($headers) && count($headers))
        {
            foreach ($headers as $httpHeader)
                {
                    header($httpHeader);
                }
        }
        echo $data;
        exit;
    }
}