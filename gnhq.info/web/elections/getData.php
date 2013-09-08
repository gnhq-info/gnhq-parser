<?php
require_once('../../webinclude.php');

require_once('getDataObjects.php');

if (!defined('USE_UIK_CACHE')) {
    define('USE_UIK_CACHE', true);
}
if (!defined('USE_VIOL_CACHE')) {
    define('USE_VIOL_CACHE', true);
}
if (!defined('SHOW_RESULTS')) {
    define('SHOW_RESULTS', true);
}

if (empty($_GET['ProjectCode'])) {
    $_GET['ProjectCode'] = null;
}
if (isset($_GET['loadViol']) && 'null' == $_GET['loadViol']) {
    $_GET['loadViol'] = null;
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
    $vGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Violation');
    $viol = $vGateway->find($projectCode, $projectId);

} else {

    $regionNum = intval($_GET['regionNum']);
    $skippedRegionNums = array();
    if ($regionNum < 0) {
        $skippedRegionNums[] = -1 * $regionNum;
        $regionNum = null;
    }


    $okrugAbbr = null;

    if (isset($_GET['uikNum'])) {
        $uikNum = intval($_GET['uikNum']);
    } else {
        $uikNum = null;
    }

    if (isset($_GET['tikNum'])) {
        $tikNum = intval($_GET['tikNum']);
    } else {
        $tikNum = null;
    }
    /* далее все входные данные очищены */



    $vTypeCount = array();
    $vshort = array();

    if (!empty($_GET['loadViol'])) {
        $vGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Violation');

        $loadedAfter = null;
        if ($_GET['loadViol'] != 1) {
            $loadedAfter = date('Y-m-d H:i:s', strtotime($_GET['loadViol']));
        }
        $vshort = $vGateway->setUseCache(USE_VIOL_CACHE)->short(null, null, null, null, null, $loadedAfter);
        $violInnerCount = 0;


        foreach ($vshort as $k => $viol) {
            if (!isset($vTypeCount[$viol->getMergedTypeId()])) {
                $vTypeCount[$viol->getMergedTypeId()] = 0;
            }
            $vshort[$k] = $viol->getParams();
            $vTypeCount[$viol->getMergedTypeId()]++;
            $violInnerCount++;
        }
    }

    // $PRINT_QUERIES = 'WEB';
    // uiks
    $uikRGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Uik');
    $uikCount = $uikRGateway->setUseCache(USE_UIK_CACHE)->getCount($regionNum, $okrugAbbr, $tikNum, array($uikNum));
    $uiks = array();
    if ($tikNum && !$uikNum) {
        foreach ($uikRGateway->getUiks($regionNum, $okrugAbbr, $tikNum, null, $projectCode) as $uik) {
            /* @var $uik ParserIKData_Model_UIKRussia */
            $uiks[$uik->getFullName()] = $uik->getUikNum();
        }
    }


    // twitter feed
    $twitGateway = new ParserIKData_Gateway_Twit();
    $newTwits = $twitGateway->getAll(20);
    $twitData = array();
    foreach ($newTwits as $twit) {
        $twitData[] = array('time' => $twit->getTime(), 'html' => $twit->getHtml(), 'link' => $twit->getLink());
    }

    // результаты
    $resultProjectCodes;
    if (!$projectCode) {
        $resultProjectCodes = array();
    } else {
        $resultProjectCodes = $projectCode;
    }
    if (!empty($_GET['onlyClean'])) {
        $codeString = implode('|', $resultProjectCodes);
        $codeString = str_replace(PROJECT_GN, PROJECT_GL, $codeString);
        $resultProjectCodes = explode('|', $codeString);
    }

    if (!empty($_GET['onlyControlRelTrue'])) {
        $onlyControlRelTrue = true;
    } else {
        $onlyControlRelTrue = false;
    }

    if ($regionNum) {
        $protocolGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Protocol');
        $protocolGateway->setUseCache(USE_UIK_CACHE);
        $ofGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_ProtocolOf');
        $ofGateway->setUseCache(USE_UIK_CACHE);
        $watchersResult = $protocolGateway->getMixedResult($regionNum, $okrugAbbr, $tikNum, $uikNum, $resultProjectCodes, $onlyControlRelTrue, true);
        $ofResult = $ofGateway->getMixedResult($regionNum, $okrugAbbr, $tikNum, $uikNum, ParserIKData_Model_Protocol::TYPE_OF, $onlyControlRelTrue, true);
        // $watchersResult = $protocolGateway->getMixedResult($regionNum, $okrugAbbr, null, 'OF', false, false, false, false);
    }
}

// формат ответа
$response = new stdClass();
$response->lastUpdated = date('Y-m-d H:i:s');
if ($modeSingleViolation) {
    $violParams = $viol->getParams();
    $violParams['Media'] = $viol->getMediaAsArray();
    $response->violData = $violParams;
} else {
    $response->regionNum = $regionNum;
    $response->vshort = $vshort;
    $response->vTypeCount = $vTypeCount;
    $response->twits = $twitData;
    $response->uikCnt = $uikCount;
    if ($uiks) {
        $response->uiks = $uiks;
    } else {
        $response->uiks = 0;
    }
    if (isset($usedRegionNums)) {
        $response->usedRegionNums = $usedRegionNums;
    }

    if (SHOW_RESULTS) {
        if (!empty($watchersResult) && $watchersResult instanceof ParserIKData_Model_Protocol) {
            $response->watchersData =  $watchersResult->getDiagramData(true, 2);
            $response->watchersUIKCount = $watchersResult->getUikCount();
            $response->ofData = $ofResult->getDiagramData(true, 2);
            $response->ofUIKCount = $ofResult->getUikCount();
        } else {
            $response->watchersData =  $watchersResultData;
            $response->watchersUIKCount = $watchersResultUikCount;
            $response->ofData = $ofResultData;
            $response->ofUIKCount = $ofResultUikCount;
        }
    }

    // если не только GN - явка некорректная
    if (in_array(PROJECT_GOLOS, $resultProjectCodes)) {
        $response->watchersData['AT'] = 0;
        $response->ofData['AT'] = 0;
    }
}

if (!empty($_GET['violcsv'])) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-disposition: attachment;filename=violations.csv');

    foreach ($response->vshort as $v) {
        if (!isset($fldHeaders)) {
            $fldHeaders = array_keys($v);
            $fldHeaders = array_map(function ($str) {
                return str_replace('"', '\\"', $str);
            }, $fldHeaders);
            print '"' . implode ('","', $fldHeaders) . '"' . PHP_EOL;
        }
        $flds = array_values($v);
        $flds = array_map(function ($str) {return str_replace('"', '\\"', $str);}, $flds);
        print '"' . implode ('","', $flds) . '"' . PHP_EOL;
    }
} else {
    header('Content-Type: application/json');
    print json_encode($response);
}