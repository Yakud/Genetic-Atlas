<?php

require_once __DIR__ . "/bootstrap/bootstrap.php";


header('Content-Type: application/json');
$SearchStorage = new \Atlas\Marker\MarkerSearchStorage();
$range = $SearchStorage->getAgesRange();

echo json_encode([
    'range' => $range,
]);