<?php

require_once(dirname(__FILE__) . '/Controller/accessibleFunctions.php');
require_once(dirname(__FILE__) . '/Schema/Modifiers.php');

$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uriSegments[3] !== null && $uriSegments[3] !== 'cars' || $uriSegments[4] === null) {
    header('HTTP/1.1 404 Not Found');
    exit();
} else if ($uriSegments[3] !== null && $uriSegments[3] == 'cars' && $uriSegments[4] === 'create') {
    $action = new Accessible();
    $queryType = $uriSegments[4] . 'Data';
    $action->{$queryType}();
} else {
    $action = new Accessible();
    $queryType = $uriSegments[4] . 'Data';
    $action->{$queryType}();
}
