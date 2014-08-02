<?php
use Routing\Exception\RouteNotFoundException;
use Routing\Router;
use View\ViewFacade;

require_once __DIR__ . '/bootstrap/bootstrap.php';
$Router = new Router();

try {
    $Controller = $Router->getPageController();
} catch (RouteNotFoundException $Ex) {
    http_response_code(404);
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");

    echo ViewFacade::make('error/404');
    die();
}
$content = $Controller->runEvent();
if (is_string($content)) {
    echo $content;
}