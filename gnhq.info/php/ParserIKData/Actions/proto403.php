<?php
include_once 'include.php';

$gw = new ParserIKData_Gateway_Protocol403();
$data = $gw->getMixedResult(77, null, 101, 772050, array('GN'), true, true);
var_dump($data->getDiagramData(true, 2));
var_dump($data->getUikCount());