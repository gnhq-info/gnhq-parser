<?php
define('PROJECT_STARTED', 1);
include 'webinclude.php';

if (empty($_SERVER['HTTP_REFERER'])) {
    trigger_error('No referer');
    exit(1);
}
$fullHost = 'http://'.$_SERVER['HTTP_HOST'];

if (substr($_SERVER['HTTP_REFERER'], -8) != 'viol.php' || substr($_SERVER['HTTP_REFERER'], 0, strlen($fullHost)) != $fullHost) {
    trigger_error('Bad referer: '.substr($_SERVER['HTTP_REFERER'], -8) . '|' . substr($_SERVER['HTTP_REFERER'], 0, strlen($fullHost)), E_USER_ERROR);
    exit(1);
}
unset($fullHost);

/* validating input params */
if (empty($_GET['ProjectCode'])) {
    $_GET['ProjectCode'] = null;
}
if (is_array($_GET['ProjectCode'])) {
    if (empty($_GET['ProjectCode'])) {
        $projectCode = null;
    } else {
        $projectCode = array();
        foreach ($_GET['ProjectCode'] as $prCode) {
            if (array_key_exists(strval($prCode), $PROJECT_CONFIG)) {
                $projectCode[] = $prCode;
            }
        }
        if (empty($projectCode)) {
            $projectCode = null;
        }
    }
} else {
    if (strtolower($_GET['ProjectCode']) == 'null') {
        $projectCode = null;
    } else {
        $projectCode = substr($_GET['ProjectCode'], 0, 2);
    }
    if ($projectCode && !array_key_exists($projectCode, $PROJECT_CONFIG)) {
        trigger_error('Bad projectCode: '.$projectCode, E_USER_ERROR);
        exit(2);
    }
}


$modeSingleViolation = (!empty($_GET['isSingle']) ? true : false);

if ($modeSingleViolation) {

    $projectId = (isset($_GET['ProjectId']) ? $_GET['ProjectId'] : '');
    $vGateway = new ParserIKData_Gateway_Violation();
    $viol = $vGateway->find($projectCode, $projectId);

} else {

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
    // caching for 120 seconds - set in ParserIKData_Gateway_Violation->_getCacheLifetime();
    $vshort = $vGateway->setUseCache(true)->short($projectCode, null, $regionNum, $okrugTikNums);
    $vTypeCount = array();
    foreach ($vshort as $k => $viol) {
        $vshort[$k] = $viol->getParams();
        if (!isset($vTypeCount[$viol->getMergedTypeId()])) {
            $vTypeCount[$viol->getMergedTypeId()] = 0;
        }
        $vTypeCount[$viol->getMergedTypeId()]++;
    }
    $count = count($vshort);
}

// twitter feed
$twitGateway = new ParserIKData_Gateway_Twit();
$newTwits = $twitGateway->getAll(10);
$twitData = array();
foreach ($newTwits as $twit) {
    $twitData[] = array('time' => $twit->getTime(), 'html' => $twit->getHtml());
}
// формат ответа



$response = new stdClass();
if ($modeSingleViolation) {
    $violParams = $viol->getParams();
    $violParams['Media'] = $viol->getMediaAsArray();
    $response->violData = $violParams;
} else {
    $response->cnt = $count;
    $response->regionNum = $regionNum;
    $response->vshort = $vshort;
    $response->vTypeCount = $vTypeCount;
}
$response->twits = $twitData;

header('Content-Type: application/json');
print json_encode($response);