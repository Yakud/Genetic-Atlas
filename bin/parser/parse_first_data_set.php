<?php
use Atlas\Point\Parser\FirstDataSetParser;

require_once __DIR__ . '/../../bootstrap/bootstrap.php';

$Parser = new FirstDataSetParser();
$Parser->parse();