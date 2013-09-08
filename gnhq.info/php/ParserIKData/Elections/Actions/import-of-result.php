<?php
$uikGateway = ParserIKData_ServiceLocator::getInstance()
		->getService('Gateway_Uik');
$protoGateway = ParserIKData_ServiceLocator::getInstance()
		->getService('Gateway_ProtocolOf');
//$PRINT_QUERIES = 'WEB';
$uiks = $uikGateway->getAll();
$site = ParserIKData_ServiceLocator::getInstance()->getService('Site_CikUIK');

$timeStart = microtime(true);
foreach ($uiks as $uik) {
	$proto = $site->getOficialProtocol($uik);
	$uikData = $proto->getData();
	if (empty($uikData)) {
	    print ($uik->getFullName() . ' empty'. PHP_EOL);
		continue;
	}
	try {
		$protoGateway->insert($proto);
		print($uik->getFullName() .  ' loaded ----------------------' . PHP_EOL);
	} catch (Exception $e) {
	    print($uik->getFullName() . ' ' . $e->getMessage() .  PHP_EOL);
	    error_log($uik->getFullName() . ' ' . $e->getMessage());
	}
}
$timeEnd = microtime(true);
print PHP_EOL . sprintf('total time in sec: %.2F', ($timeEnd - $timeStart));
