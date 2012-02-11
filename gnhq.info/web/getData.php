<?php
define('PROJECT_STARTED', 1);
include 'webinclude.php';


/* validating input params */
$warehouse->loadAllOkrugs();
$okrugAbbr = isset($_GET['okrugAbbr']) ? $_GET['okrugAbbr'] : null;
$okrugAbbrOk = false;
foreach (ParserIKData_Model_Okrug::getAllOBjects() as $okrug) {
    /* @var $okrug ParserIKData_Model_Okrug */
    if ($okrugAbbr == $okrug->getAbbr()) {
        $okrugAbbrOk = true;
    }
}
if (!$okrugAbbrOk) {
    $okrugAbbr = null;
}

$uik = isset($_GET['uik']) ? intval($_GET['uik']) : null;

$selectionType = isset($_GET['selectionType']) ? $_GET['selectionType'] : SELECTION_TYPE_DEFAULT;
if (!in_array($selectionType, array(SELECTION_TYPE_DEFAULT, SELECTION_TYPE_PROTOCOL, SELECTION_TYPE_CLEAN, SELECTION_TYPE_DISCREPANCY))) {
    $selectionType = SELECTION_TYPE_DEFAULT;
}
/* далее все входные данные очищены */


$watchGateway = new ParserIKData_Gateway_Watch412();
$protocolGateway = new ParserIKData_Gateway_Protocol412();
$reportGateway = new ParserIKData_Gateway_Report412();
$inPercent = true;
$digits = 2;
$onlyProtocol = false;
$onlyClean = false;
$onlyWithDiscrepancy = false;
if ($selectionType == SELECTION_TYPE_PROTOCOL) {
    $onlyProtocol = true;
}
if ($selectionType == SELECTION_TYPE_CLEAN) {
    $onlyProtocol = true;
    $onlyClean = true;
}
if ($selectionType == SELECTION_TYPE_DISCREPANCY) {
    $onlyProtocol = true;
    $onlyWithDiscrepancy = true;
}

// формат ответа
/*
 * mode 			=> режим (uik - участок, oik - okrug, rik - регион)
 * uiks 			=> массив номеров участковых комиссий по выборке
 * totalCount   	=> общее число участков в выборке
 * discrepancyCount => число участков с расхождением в протоколах
 * ofResult         => результаты официальные
 * gnResult         => результаты ГН
 */
$response = new stdClass();
$response->uiks = array();

if ($uik) {
    // режим УИК
    $response->mode = DISPLAY_MODE_UIK;
    $response->ofResult = $protocolGateway->getMixedResult(null, $uik, null, false, false, false)->getDiagramData($inPercent, $digits);
    $response->gnResult = $protocolGateway->getMixedResult(null, $uik, WATCH_GN, false, false, false)->getDiagramData($inPercent, $digits);
    $response->hasProtocol = 1 - intval($protocolGateway->getMixedResult(null, $uik, WATCH_GN, true, false, false)->isEmpty());
    $response->reportLink = '';
    $report = $reportGateway->getForUik($uik);
    if ($report && $report->getLink()) {
        $response->reportLink = GN_SITE . $report->getLink();
    }
    $response->protocolLink = '';
} elseif ($okrugAbbr) {
    // режим Округа
    $response->mode = DISPLAY_MODE_OIK;
    $uikGateway = new ParserIKData_Gateway_UIK();
    $uiks = $uikGateway->getForOkrug($okrugAbbr, WATCH_GN, $onlyProtocol, $onlyClean, $onlyWithDiscrepancy);
    foreach ($uiks as $uik) {
        /* @var $uik ParserIKData_Model_UIK */
        $response->uiks[] = $uik->getFullName();
    }
    $response->totalCount       = $watchGateway->getCount(WATCH_GN, $okrugAbbr, false, false);
    $response->discrepancyCount = $watchGateway->getCount(WATCH_GN, $okrugAbbr, true, false);
    $response->protocolCount    = $watchGateway->getCount(WATCH_GN, $okrugAbbr, false, true);
    $response->ofResult         = $protocolGateway->getMixedResult($okrugAbbr, null, null, false, false, false)->getDiagramData($inPercent, $digits);
    $response->gnResult         = $protocolGateway->getMixedResult($okrugAbbr, null, WATCH_GN, $onlyProtocol, $onlyClean, $onlyWithDiscrepancy)->getDiagramData($inPercent, $digits);
} else {
    // режим региона (город)
    $response->mode = DISPLAY_MODE_RIK;
    $response->totalCount       = $watchGateway->getCount(WATCH_GN, null, false);
    $response->discrepancyCount = $watchGateway->getCount(WATCH_GN, null, true);
    $response->protocolCount    = $watchGateway->getCount(WATCH_GN, $okrugAbbr, false, true);
    $response->ofResult         = $protocolGateway->getMixedResult(null, null, null, false, false, false)->getDiagramData($inPercent, $digits);
    $response->gnResult         = $protocolGateway->getMixedResult(null, null, WATCH_GN, $onlyProtocol, $onlyClean, $onlyWithDiscrepancy)->getDiagramData($inPercent, $digits);
}


header('Content-Type: application/json');
print json_encode($response);