<?php
/**
 * action for (cron) tasks of importing protocol feeds
 * @argv[1] - projectCode
 */
require_once('base.php');

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

$xmlProcessor = new ParserIKData_XMLProcessor_Protocol_Krasnoyarsk($projectCode);
$gateway = new ParserIKData_Gateway_Protocol_Krasnoyarsk();

$importCodes = array();
foreach ($sXml->xpath('prt') as $pXml) {

    $newProto = $xmlProcessor->createFromXml($pXml);

    if (!$newProto instanceof ParserIKData_Model_Protocol_Krasnoyarsk) {
        @$importCodes[$newProto]++;
        continue;
    }
    $result = $xmlProcessor->updateIfNecessary($newProto);
    @$importCodes[$result]++;
}

$timeEnd = microtime(true);
print_r($importCodes);
print PHP_EOL . sprintf('total time in sec: %.2F; load time: %.2F; our time: %.2F', ($timeEnd - $timeStart), ($timeEndLoad - $timeStart), ($timeEnd - $timeEndLoad));