<?php
$uikGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Uik');
$protoGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_ProtocolOf');
$uiks = $uikGateway->getAll();
$site = ParserIKData_ServiceLocator::getInstance()->getService('Site_CikUIK');

foreach ($uiks as $uik) {
    $proto = $site->getOficialProtocol($uik);
    $protoGateway->insert($proto);
    print($uik->getFullName() . PHP_EOL);
}