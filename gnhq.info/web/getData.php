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
if (!in_array($selectionType, array(SELECTION_TYPE_DEFAULT, SELECTION_TYPE_PROTOCOL, SELECTION_TYPE_CLEAN, SELECTION_TYPE_DISCREPANCY, SELECTION_TYPE_REPORT))) {
    $selectionType = SELECTION_TYPE_DEFAULT;
}
/* далее все входные данные очищены */


$watchGateway = new ParserIKData_Gateway_Watch412();
$watchGateway->setUseCache(true);
$protocolGateway = new ParserIKData_Gateway_Protocol412();
$protocolGateway->setUseCache(true);
$reportGateway = new ParserIKData_Gateway_Report412(); // данные по отчетам не кэшируем- они регулярно выкладываются на сайт
$inPercent = true;
$digits = 2;
$onlyProtocol = false;
$onlyClean = false;
$onlyWithDiscrepancy = false;
$onlyWithReport = false;
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
if ($selectionType == SELECTION_TYPE_REPORT) {
    $onlyWithReport = true;
}

// формат ответа
/*
 * mode 			=> режим (uik - участок, oik - okrug, rik - регион)
 * uiks 			=> массив номеров участковых комиссий по выборке
 * totalCount   	=> общее число участков в выборке
 * discrepancyCount => число участков с расхождением в протоколах
 * ofResult         => результаты официальные
 * gnResult         => результаты ГН
 * ofCount          => количество участков, данные по которым учитываются в текущей статистике ЦИК
 * gnCount          => количество участков, данные по которым учитываются в текущей статистике ГН
 */
$response = new stdClass();
$response->uiks = array();

if ($uik) {
    // режим УИК
    $response->mode = DISPLAY_MODE_UIK;
    $response->ofResult = $protocolGateway->getMixedResult(null, $uik, null, false, false, false)->getDiagramData($inPercent, $digits);
    $response->gnResult = $protocolGateway->getMixedResult(null, $uik, WATCH_GN, false, false, false)->getDiagramData($inPercent, $digits);
    $response->hasProtocol = 1 - intval($protocolGateway->getMixedResult(null, $uik, WATCH_GN, true, false, false, false)->isEmpty());
    $response->reportLink = '';
    $report = $reportGateway->getForUik($uik);
    if ($report && $report->getLink()) {
        $response->reportLink = GN_SITE . $report->getLink();
    }
    $response->protocolLink = '';
    $response->ofCount = 1;
    $response->gnCount = 1;
} elseif ($okrugAbbr) {
    // режим Округа
    $response->mode = DISPLAY_MODE_OIK;
    $uikGateway = new ParserIKData_Gateway_UIK();
    $uikGateway->setUseCache(true);
    $uiks = $uikGateway->getForOkrug($okrugAbbr, WATCH_GN, $onlyProtocol, $onlyClean, $onlyWithDiscrepancy, $onlyWithReport);
    foreach ($uiks as $uik) {
        /* @var $uik ParserIKData_Model_UIK */
        $response->uiks[] = $uik->getFullName();
    }
    $response->totalCount       = $watchGateway->getCount(WATCH_GN, $okrugAbbr, false, false);
    $response->discrepancyCount = $watchGateway->getCount(WATCH_GN, $okrugAbbr, true, false);
    $response->protocolCount    = $watchGateway->getCount(WATCH_GN, $okrugAbbr, false, true);
    $ofResult                   = $protocolGateway->getMixedResult($okrugAbbr, null, null, false, false, false, false);
    $response->ofResult         = $ofResult->getDiagramData($inPercent, $digits);
    $response->ofCount          = $ofResult->getUikCount();
    $gnResult                   = $protocolGateway->getMixedResult($okrugAbbr, null, WATCH_GN, $onlyProtocol, $onlyClean, $onlyWithDiscrepancy, $onlyWithReport);
    $response->gnResult         = $gnResult->getDiagramData($inPercent, $digits);
    $response->gnCount          = $gnResult->getUikCount();
} else {
    // режим региона (город)
    $response->mode = DISPLAY_MODE_RIK;
    $response->totalCount       = $watchGateway->getCount(WATCH_GN, null, false);
    $response->discrepancyCount = $watchGateway->getCount(WATCH_GN, null, true);
    $response->protocolCount    = $watchGateway->getCount(WATCH_GN, $okrugAbbr, false, true);
    $ofResult                   = $protocolGateway->getMixedResult(null, null, null, false, false, false, false);
    $response->ofResult         = $ofResult->getDiagramData($inPercent, $digits);
    $response->ofCount          = $ofResult->getUikCount();
    $gnResult                   = $protocolGateway->getMixedResult(null, null, WATCH_GN, $onlyProtocol, $onlyClean, $onlyWithDiscrepancy, $onlyWithReport);
    $response->gnResult         = $gnResult->getDiagramData($inPercent, $digits);
    $response->gnCount          = $gnResult->getUikCount();
}


header('Content-Type: application/json');
print json_encode($response);