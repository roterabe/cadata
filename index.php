<?php

require_once(dirname(__FILE__) . '/Controller/accessibleFunctions.php');
require_once(dirname(__FILE__) . '/Schema/Modifiers.php');

class acceptRequest
{
    private $uriSegments = '';

    public function __construct($url)
    {
        $this->uriSegments = explode('/', parse_url($url, PHP_URL_PATH));
    }

    public function getUri($pos)
    {
        return $this->uriSegments[$pos];
    }

}

;

$uri = new acceptRequest($_SERVER['REQUEST_URI']);

if ($uri->getUri(3) !== null && $uri->getUri(3) !== 'cars' || $uri->getUri(4) === null) {
    header('HTTP/1.1 404 Not Found');
    exit();
} else if ($uri->getUri(3) !== null && $uri->getUri(3) === 'cars' && $uri->getUri(4) === 'create') {
    $action = new accessibleFunctions('SQLite');
    $queryType = $uri->getUri(4) . 'Data';
    $action->{$queryType}();
} else if ($uri->getUri(3 !== null && $uri->getUri(3) === 'cars' && $uri->getUri(4) === 'update')) {
    $action = new accessibleFunctions('SQLite');
    $queryType = $uri->getUri(4) . 'Data';
    $action->{$queryType}();
} else if ($uri->getUri(3) !== null && $uri->getUri(3) === 'cars' && $uri->getUri(4) === 'delete') {
    $action = new accessibleFunctions('SQLite');
    $queryType = $uri->getUri(4) . 'Data';
    $action->{$queryType}();
} else {
    $action = new accessibleFunctions('SQLite');
    $queryType = $uri->getUri(4) . 'Data';
    $action->{$queryType}();
}
