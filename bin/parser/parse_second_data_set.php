<?php
use Atlas\Point\Parser\SecondDataSetParser;

require_once __DIR__ . '/../../bootstrap/bootstrap.php';

$Parser = new SecondDataSetParser();
$Parser->parse();