<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_Protocol403();
//$gateway->setUseCache(false);

$res = $gateway->getMixedResult(null, null, null, array('GN', 'AG'), false);
var_dump($res->getUikCount());
var_dump($res->getDiagramData(true, 2));
