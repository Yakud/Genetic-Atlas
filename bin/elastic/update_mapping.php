<?php
require_once __DIR__ . '/../../bootstrap/bootstrap.php';

$Storage = new \Atlas\Marker\MarkerElasticStorage();
$Storage->getType()->delete();
$Storage->getIndex()->delete();

$Storage->createIndex();
$Storage->updateMapping();
