<?php
include_once 'include.php';

$xmlProcessor = new ParserIKData_XML_Violation();
$gateway = new ParserIKData_Gateway_Violation();

$xml = file_get_contents(DATA_PATH . 'viol1.xml');
$newViol = $xmlProcessor->createFromXml($xml, PROJECT_GN);

if (!$newViol instanceof ParserIKData_Model_Violation) {
    print 'invalid data' . $newViol;
    return;
}

$current = $gateway->find($newViol->getProjectCode(), $newViol->getProjectId());

if ($current === null) {
    $gateway->insert($newViol);
} else {
    $gateway->update($newViol);
}
