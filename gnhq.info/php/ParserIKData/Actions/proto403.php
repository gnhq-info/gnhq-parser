<?php
include_once 'include.php';

$projectCode = PROJECT_SMS_EXITPOLE;

if (empty($PROJECT_CONFIG[$projectCode])) {
    print 'wrong code';
    return;
}

$projectFeed = $PROJECT_CONFIG[$projectCode]['ProtoLink'];

if (!$projectFeed) {
    print 'no feed link';
    return;
}

$xmlProcessor = new ParserIKData_XMLProcessor_Protocol403($projectCode);
$gateway = new ParserIKData_Gateway_Protocol403();


$sXml = simplexml_load_file($projectFeed);
if (!$sXml instanceof SimpleXMLElement) {
    print('bad xml');
    return;
}

foreach ($sXml->xpath('prt') as $pXml) {

    $newProto = $xmlProcessor->createFromXml($pXml);

    if (!$newProto instanceof ParserIKData_Model_Protocol403) {
        print $newProto . PHP_EOL;
        continue;
    }
    $result = $xmlProcessor->updateIfNecessary($newProto);
    print $result . PHP_EOL;
}