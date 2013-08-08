<?php
$projectCode = $argv[1];


if (empty($PROJECT_CONFIG[$projectCode])) {
    print 'wrong code '.$projectCode;
    return;
}

$projectFeed = $PROJECT_CONFIG[$projectCode]['ProtoLink'];

if (!$projectFeed) {
    print 'no feed link';
    return;
}
$timeStart = microtime(true);
$sXml = simplexml_load_file($projectFeed);
if (!$sXml instanceof SimpleXMLElement) {
    print('bad xml');
    return;
}
$timeEndLoad = microtime(true);

$xmlProcessor = ParserIKData_ServiceLocator::getInstance()->getService('XmlProcessor_Protocol');
$gateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Protocol');

$importCodes = array();
foreach ($sXml->xpath('prt') as $pXml) {

    $newProto = $xmlProcessor->createFromXml($pXml);

    if (!$newProto instanceof ParserIKData_Model_Protocol) {
        @$importCodes[$newProto]++;
        continue;
    }
    $result = $xmlProcessor->updateIfNecessary($newProto);
    @$importCodes[$result]++;
}

$timeEnd = microtime(true);
print_r($importCodes);
print PHP_EOL . sprintf('total time in sec: %.2F; load time: %.2F; our time: %.2F', ($timeEnd - $timeStart), ($timeEndLoad - $timeStart), ($timeEnd - $timeEndLoad));