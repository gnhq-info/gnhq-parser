<?php
$projectCode = $argv[1];


if (empty($PROJECT_CONFIG[$projectCode])) {
    print 'wrong code '.$projectCode.PHP_EOL;
    return;
}

$projectFeed = !empty($PROJECT_CONFIG[$projectCode]['ProtoLink']) ? $PROJECT_CONFIG[$projectCode]['ProtoLink'] : '';

if (!$projectFeed) {
    print 'no feed link for '. $projectCode .PHP_EOL;
    return;
}
$timeStart = microtime(true);
$oData = json_decode(file_get_contents($projectFeed));
if (!$oData || !$oData instanceof stdClass) {
    print 'bad data: '.PHP_EOL;
}
$timeEndLoad = microtime(true);

$xmlProcessor = ParserIKData_ServiceLocator::getInstance()->getService('XmlProcessor_Protocol');
$gateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Protocol');

$importCodes = array();
foreach ($oData->election_valid_results->valid_results as $result) {

    $newProto = $xmlProcessor->createFromXml($result);

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