<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_UIKRussia();
$res = $gateway->getCount(77, 'вао', '');
var_dump($res);