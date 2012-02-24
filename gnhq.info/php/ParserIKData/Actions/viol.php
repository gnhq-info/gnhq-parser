<?php
include_once 'include.php';

$xmlProcessor = new ParserIKData_XMLProcessor_Violation();
$gateway = new ParserIKData_Gateway_Violation();

$xml = file_get_contents(DATA_PATH . 'viol1.xml');
$newViol = $xmlProcessor->createFromXml($xml, PROJECT_GN);

if (!$newViol instanceof ParserIKData_Model_Violation) {
    print 'invalid data' . $newViol;
    return;
}

$currentViol = $gateway->find($newViol->getProjectCode(), $newViol->getProjectId());
$result = $xmlProcessor->updateIfNecessary($newViol, $currentViol);
print $result;