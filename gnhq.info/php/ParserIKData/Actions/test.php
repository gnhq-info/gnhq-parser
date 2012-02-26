<?php
include_once 'include.php';
$trGateway = new ParserIKData_Gateway_TIKRussia();
$tiks = $trGateway->setUseCache(true)->getForRegionAndOkrug(77, 'ЮАО');

foreach ($tiks as $tik) {
    var_dump($tik->getFullName());
    var_dump($tik->getOkrugName());
}
