<?php
include_once 'include.php';

$xmlProcessor = new ParserIKData_XMLProcessor_Protocol403(PROJECT_GN);
$gateway = new ParserIKData_Gateway_Protocol403();


$sXml = simplexml_load_file('http://gnhq.info/export/protocols.xml');
if (!$sXml instanceof SimpleXMLElement) {
    die('bad sxml');
}

foreach ($sXml->xpath('prt') as $pXml) {
    echo 'next prt'.PHP_EOL;

    $newProto = $xmlProcessor->createFromXml($pXml);

    if (!$newProto instanceof ParserIKData_Model_Protocol403) {
        print 'invalid data' . $newProto;
        continue;
    }
    $result = $xmlProcessor->updateIfNecessary($newProto);
    print $result . PHP_EOL;
}