<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_ViolationType();
$gateway->setUseCache(true);
$codes = $gateway->getMergedTypesByProjectTypes('GN');
ksort($codes);
var_dump($codes);