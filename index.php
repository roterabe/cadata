<?php

require 'Controller/accesibleFunctions.php';
require 'Schema/Modifiers.php';

$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uriSegments[2] !== null && $uriSegments[2] !== 'user')
