<?php
define('PROJECT_STARTED', 1);
include 'webinclude.php';

if (str_replace('http://' . rtrim($_SERVER['HTTP_HOST'],'/') . '/', '', $_SERVER['HTTP_REFERER']) != 'viol.php') {
    trigger_error('Bad referer: '.$_SERVER['HTTP_REFERER'], E_USER_ERROR);
    exit(1);
}


/* validating input params */
$projectCode = substr($_GET['ProjectCode'], 0, 2);
if ($projectCode && !array_key_exists($projectCode, $PROJECT_CONFIG)) {
    trigger_error('Bad projectCode: '.$projectCode, E_USER_ERROR);
    exit(2);
}
$mergedTypeId = $_GET['ViolType'];
if ($mergedTypeId === '') {
    $mergedTypeId = null;
} else {
    $mergedTypeId = intval($mergedTypeId);
}

$regionNum = intval($_GET['regionNum']);

$warehouse->loadAllOkrugs();
$okrugAbbr = isset($_GET['okrug']) ? $_GET['okrug'] : null;
$okrugAbbrOk = false;
$okrugTikNums = null;
foreach (ParserIKData_Model_Okrug::getAllOBjects() as $okrug) {
    /* @var $okrug ParserIKData_Model_Okrug */
    if ($okrugAbbr == $okrug->getAbbr()) {
        $okrugAbbrOk = true;
    }
}
if ($okrugAbbrOk) {
    $tikGateway = new ParserIKData_Gateway_TIKRussia();
    $okrugTiks = $tikGateway->setUseCache(true)->getForRegionAndOkrug($regionNum, $okrugAbbr);
    foreach ($okrugTiks as $oTik) {
        $okrugTikNums[] = $oTik->getTikNum();
    }
}
/* далее все входные данные очищены */


$vGateway = new ParserIKData_Gateway_Violation();
$vshort = $vGateway->short($projectCode, $mergedTypeId, $regionNum, $okrugTikNums);
$tikCount = array();
foreach ($vshort as $k => $viol) {
    $vshort[$k] = $viol->getParams();
    if (!isset($tikCount[$viol->getTIKNum()])) {
        $tikCount[$viol->getTIKNum()] = 0;
    }
    $tikCount[$viol->getTIKNum()]++;
}
$count = count($vshort);

// формат ответа

$response = new stdClass();
$response->cnt = $count;
$response->regionNum = $regionNum;
$response->vshort = $vshort;
$response->tikCount = $tikCount;


header('Content-Type: application/json');
print json_encode($response);