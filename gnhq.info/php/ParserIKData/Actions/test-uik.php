<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_UIKRussia();
$protoGateway = new ParserIKData_Gateway_Protocol403();

$cond = $protoGateway->getCondHasProto(array('GN'), 77, 1);
var_dump($cond);
die();


/**
 * @param ParserIKData_Model_UIKRussia $uik
 */
function _debugPringUik($uik)
{
    print str_pad($uik->getUniqueId(), 7, ' ', STR_PAD_LEFT) . ' ' . str_pad($uik->getUikNum(), 4, ' ', STR_PAD_LEFT) . PHP_EOL;
}