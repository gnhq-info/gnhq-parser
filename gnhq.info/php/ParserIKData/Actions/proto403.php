<?php
include_once 'include.php';

$xmlProcessor = new ParserIKData_XMLProcessor_Protocol403();
$gateway = new ParserIKData_Gateway_Protocol403();


$sXml = simplexml_load_file('http://gnhq.info/export/protocols.xml');
if (!$sXml instanceof SimpleXMLElement) {
    die('bad sxml');
}

foreach ($sXml->xpath('prt') as $pXml) {
    echo 'next prt'.PHP_EOL;

    $newProto = $xmlProcessor->createFromXml($pXml, PROJECT_GN);

    if (!$newProto instanceof ParserIKData_Model_Protocol403) {
        print 'invalid data' . $newProto;
        continue;
    }

    $currentProto = null;//$gateway->find($newViol->getProjectCode(), $newViol->getProjectId());
    $result = $xmlProcessor->updateIfNecessary($newProto, $currentProto);
    print $result . PHP_EOL;
}