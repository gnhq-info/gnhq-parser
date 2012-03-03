<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_Protocol403();
$res = $gateway->getMixedResult(77, null, null, PROJECT_GOLOS);
var_dump($res->getDiagramData(true, 2));