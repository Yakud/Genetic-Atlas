<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require_once __DIR__ . "/bootstrap/path.php";
require_once PATH_VENDOR . "/autoload.php";

$View = new \View\View();
$View->setTitle('Hello');

echo $View->make('test');