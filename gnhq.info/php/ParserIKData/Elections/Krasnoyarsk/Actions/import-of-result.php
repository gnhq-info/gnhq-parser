<?php
/**
 * import official results from cik site
 */
require_once 'base.php';

$uikGateway = new ParserIKData_Gateway_Uik_Krasnoyarsk();
$protoGateway = new ParserIKData_Gateway_Protocol_KrasnoyarskOf();
$uiks = $uikGateway->getAll();
$site = new ParserIKData_Site_CikUIK_Krasnoyarsk();

foreach ($uiks as $uik) {
    $proto = $site->getOficialProtocol($uik);
    $protoGateway->insert($proto);
    print($uik->getFullName() . PHP_EOL);
}