<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_Violation();
$v = $gateway->short('GN', null, 77, null, null);
var_dump($v);