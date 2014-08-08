<?php
use Atlas\Point\PointElasticSearchStorage;

require_once __DIR__ . '/../../bootstrap/bootstrap.php';

$Storage = new PointElasticSearchStorage();
$Storage->createIndex();
$Storage->updateMapping();