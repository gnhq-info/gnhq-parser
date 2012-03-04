<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_UIKRussia();

$tikNum = $gateway->setUseCache(true)->findTikNumByRegionAndUik(77, 2890);
print_r($tikNum);