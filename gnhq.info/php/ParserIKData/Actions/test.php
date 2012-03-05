<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_Protocol403();
//$gateway->setUseCache(false);

$regionNum = null;
$watchers = array('GN', 'AG');

$res = $gateway->getMixedResult($regionNum, null, null, $watchers, false, false);
var_dump($res->getUikCount());
var_dump($res->getDiagramData(true, 2));

$res = $gateway->getMixedResult($regionNum, null, null, $watchers, false, true);
var_dump($res->getUikCount());
var_dump($res->getDiagramData(true, 2));
