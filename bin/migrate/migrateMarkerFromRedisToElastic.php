<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

$StorageRedis   = new \Atlas\Marker\MarkerRedisStorage();
$StorageElastic = new \Atlas\Marker\MarkerElasticStorage();

$markersIds = $StorageRedis->getIds(0);
$Markers = $StorageRedis->getByIds($markersIds);

foreach ($Markers as $Marker) {
    $StorageElastic->save($Marker);
    echo "Index marker {$Marker->get('id')}\n";
}
