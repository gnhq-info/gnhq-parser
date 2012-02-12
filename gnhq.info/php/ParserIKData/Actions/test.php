<?php
include_once 'include.php';
$electionCode = '412';
/**
 * @var ParserIKData_Warehouse_Interface
 */
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
//$warehouse->loadAllOkrugs();


$gateway = new ParserIKData_Gateway_Protocol412();



$okrugAbbr = 'ЗелАО';
$uikNum = null;
$watchOf = null;
$watchGn = ParserIKData_Model_Protocol412::TYPE_GN;

$warehouse->loadAllOkrugs();

// foreach (ParserIKData_Model_Okrug::getAllOBjects() as $okrug) {
//     /* @var $okrug ParserIKData_Model_Okrug */
//     $okrugAbbr = $okrug->getAbbr();

//     echo str_repeat(' ', 10) . $okrugAbbr . PHP_EOL;

    $off = $gateway->getUikCount($okrugAbbr, $uikNum, $watchOf, false, false, false);
    var_dump($off);

    $gn = $gateway->getUikCount($okrugAbbr, $uikNum, $watchGn, false, false, false);
    var_dump($gn);

    $gnProtocol = $gateway->getUikCount($okrugAbbr, $uikNum, $watchGn, true, false, false);
    var_dump($gnProtocol);

    $gnProtocolClean = $gateway->getUikCount($okrugAbbr, $uikNum, $watchGn, true, true, false);
    var_dump($gnProtocolClean);

    $gnProtocolDiscr = $gateway->getUikCount($okrugAbbr, $uikNum, $watchGn, true, false, true);
    var_dump($gnProtocolDiscr);

// }


function _debugPringDiagram($diagData)
{
    foreach ($diagData as $k => $v) {
        print _debugFormatKey($k) . _debugFormatValue($v) . '     ';
    }
    print array_sum($diagData) - $diagData['AT'];
    print PHP_EOL . str_repeat('-', 20) . PHP_EOL;
}

function _debugFormatKey($key)
{
    return str_pad($key, 3, ' ', STR_PAD_RIGHT);
}

function _debugFormatValue($val)
{
    return str_pad($val, 5, ' ', STR_PAD_LEFT);
}