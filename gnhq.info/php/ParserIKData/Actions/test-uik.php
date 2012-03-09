<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_UIKRussia();


$uiks = $gateway->getUiks(77, null, 1, null);
foreach ($uiks as $uik) {
    _debugPringUik($uik);
}

/**
 * @param ParserIKData_Model_UIKRussia $uik
 */
function _debugPringUik($uik)
{
    print str_pad($uik->getUniqueId(), 7, ' ', STR_PAD_LEFT) . ' ' . str_pad($uik->getUikNum(), 4, ' ', STR_PAD_LEFT) . PHP_EOL;
}