<?php
include_once 'include.php';

$vGateway = new ParserIKData_Gateway_Violation();
var_dump($vGateway->short('GN', null, 77, null));