<?php

require_once __DIR__ . "/bootstrap/bootstrap.php";

header('Content-Type: application/json');


$StorageRedis   = new \Atlas\Marker\MarkerRedisStorage();
$StorageElastic = new \Atlas\Marker\MarkerElasticStorage();

$markersIds = $StorageRedis->getIds(0);
$Markers = $StorageRedis->getByIds($markersIds);

foreach ($Markers as $Marker) {
    $result[] = $Marker->export();
}

echo json_encode([
    'points' => $result,
]);