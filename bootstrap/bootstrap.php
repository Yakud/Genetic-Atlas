<?php
define('PATH_ROOT',   __DIR__ . '/..');
define('PATH_VENDOR', PATH_ROOT . '/vendor');
define('PATH_CONFIG', PATH_ROOT . '/config');
define('PATH_VIEW',   PATH_ROOT . '/templates');
define('PATH_SRC',    PATH_ROOT . '/src');

require_once PATH_VENDOR . '/autoload.php';

session_start();

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);