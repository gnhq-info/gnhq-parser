<?php
include_once 'include.php';

/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouse->loadAllOkrugs()->loadAllTiks()->loadAllUiks();
$warehouse->loadElectionResults('412', ParserIKData_Model_Protocol412::TYPE_GN);
$gnResults = ParserIKData_Model_Protocol412::getAllOBjects();

$warehouse->loadElectionResults('412', ParserIKData_Model_Protocol412::TYPE_OF);

foreach ($gnResults as $gnResult) {
    /* @var $gnResult ParserIKData_Model_Protocol412 */
    $dualResult = $gnResult->getDualProtocol();

    if (printCond($gnResult)) {
        print 'УИК № ' . $gnResult->getFullName();
        if ($gnResult->getUik() instanceof ParserIKData_Model_UIK) {
            print ' ТИК '. $gnResult->getUik()->getTik()->getFullName();
            print ' ' . $gnResult->getUik()->getTik()->getOkrug()->getAbbr() . PHP_EOL;
        }
        printUikResult($gnResult);
        printUikResult($dualResult);
        print str_repeat('-', 25);
        print PHP_EOL.PHP_EOL;
    }
}

/**
 * @param ParserIKData_Model_Protocol412 $gnResult
 * @return boolean
 */
function printCond($gnResult)
{
    if (!$gnResult->equalPartyResults($gnResult->getDualProtocol())) {
        return true;
    }
    /*if ($gnResult->getFullName() == '1235') {
        return true;
    }*/
    return false;
}

function printUikResult(ParserIKData_Model_Protocol412 $result)
{
    $string = $result->getType() . ': ' . prepareResultArray($result->getData());
    print $string . PHP_EOL;
}


function prepareResultArray($arr)
{
    $prepared = array();
    foreach ($arr as $elem) {
        $prepared[] = prepareResult($elem);
    }
    return implode('', $prepared);
}

function prepareResult($res)
{
    return str_pad($res, 5, ' ', STR_PAD_LEFT);
}