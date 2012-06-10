<?php
$dirParts = explode(DIRECTORY_SEPARATOR, rtrim(__DIR__, DIRECTORY_SEPARATOR));
array_pop($dirParts);
$pathToBasicInclude = implode(DIRECTORY_SEPARATOR, $dirParts);
unset($dirParts);
require_once $pathToBasicInclude . DIRECTORY_SEPARATOR . 'include.php';

$projectCode = $argv[1];

if (empty($PROJECT_CONFIG[$projectCode])) {
    print 'wrong code '.$projectCode;
    return;
}

$projectFeed = $PROJECT_CONFIG[$projectCode]['ViolLink'];

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

$xmlProcessor = new ParserIKData_XMLProcessor_Violation($projectCode);
$gateway = new ParserIKData_Gateway_Violation();

$importCodes = array();
foreach ($sXml->xpath('viol') as $vXml) {

    $newViol = $xmlProcessor->createFromXml($vXml);

    if (!$newViol instanceof ParserIKData_Model_Violation) {
        @$importCodes['invalid data' . $newViol]++;
        continue;
    }
    $result = $xmlProcessor->updateIfNecessary($newViol);
    @$importCodes[$result]++;
}
$timeEnd = microtime(true);
print_r($importCodes);
print PHP_EOL . sprintf('total time in sec: %.2F; load time: %.2F; our time: %.2F', ($timeEnd - $timeStart), ($timeEndLoad - $timeStart), ($timeEnd - $timeEndLoad));