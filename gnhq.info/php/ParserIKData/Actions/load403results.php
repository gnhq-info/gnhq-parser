<?php
// loading official results from cik site
include_once 'include.php';

$regionG = new ParserIKData_Gateway_Region();
$regions = $regionG->getAll();

$processor = new ParserIKData_Site_Res403();
$uikGateway = new ParserIKData_Gateway_UIKRussia();
$gateway = new ParserIKData_Gateway_Protocol403Of();

$minRegionNum = 16;

foreach ($regions as $region) {
    /* @var $region ParserIKData_Model_Region */
    $uiks = $uikGateway->getForRegion($region->getRegionNum());
    if ($region->getRegionNum() < $minRegionNum) {
        continue;
    }

    foreach ($uiks as $uik) {
        /* @var $uik ParserIKData_Model_UIKRussia */
        try {
            $href  = $processor->getResultLink($uik);
            if ($href) {
                $proto = $processor->getProtocol($uik, $href);
                $gateway->insert($proto);
            }
            print ($uik->getFullName() . ' : ' . ($href ? ' processed ' : ' skip ') . PHP_EOL);
        } catch (Exception $e) {
            print $uik->getFullName() . ' : ' . $e->getMessage() . PHP_EOL;
        }
    }
}



print ('official results loaded');
