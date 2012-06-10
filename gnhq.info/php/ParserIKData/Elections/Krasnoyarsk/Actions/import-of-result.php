<?php
/**
 * import official results from cik site
 */
require_once 'base.php';

$uikGateway = new ParserIKData_Gateway_Uik_Krasnoyarsk();
$protoGateway = new ParserIKData_Gateway_Protocol_KrasnoyarskOf();
$uiks = $uikGateway->getAll();
$site = new ParserIKData_Site_CikUIK();

foreach ($uiks as $uik) {
    $data = $site->getProtocolData($uik->getLink());

    $proto = ParserIKData_Model_Protocol_Krasnoyarsk::create();
    $proto
        ->setResultType(ParserIKData_Model_Protocol::TYPE_OF)
        ->setProjectId($uik->getFullName())
        ->setUpdateTime(date('y-m-d h:i:s'))
        ->setIkFullName($uik->getFullName())
        ->setData($data);
    $protoGateway->insert($proto);

    print($uik->getFullName() . PHP_EOL);
}