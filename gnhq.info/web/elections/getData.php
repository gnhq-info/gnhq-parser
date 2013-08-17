<?php
require_once('../../webinclude.php');

require_once('getDataObjects.php');

/* validating input params */
if (empty($_GET['loadViol'])) {
    $_GET['loadViol'] = 0;
} else {
    $_GET['loadViol'] = 1;
}


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
    // загрузка нарушений (при первой загрузке страницы, без фильтров по регионам\проектам)
    if ($_GET['loadViol'] == '1') {
        $vGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Violation');

        $vshort = $vGateway->setUseCache(true)->short(null, null, null, null, null);
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
    $uikCount = $uikRGateway->setUseCache(false)->getCount($regionNum, $okrugAbbr, $tikNum, array($uikNum));
    $uiks = array();
    if ($tikNum && !$uikNum) {
        foreach ($uikRGateway->getUiks($regionNum, $okrugAbbr, $tikNum, null, $projectCode) as $uik) {
            /* @var $uik ParserIKData_Model_UIKRussia */
            $uiks[$uik->getFullName()] = $uik->getUikNum();
        }
    }


    // twitter feed
    if ($_GET['loadViol'] == '1') {
        $twitGateway = new ParserIKData_Gateway_Twit();
        $newTwits = $twitGateway->getAll(20);
        $twitData = array();
        foreach ($newTwits as $twit) {
            $twitData[] = array('time' => $twit->getTime(), 'html' => $twit->getHtml());
        }
    } else {
        $twitData = array();
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
        $protocolGateway->setUseCache(false);
        $ofGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_ProtocolOf');
        $ofGateway->setUseCache(false);
        $watchersResult = $protocolGateway->getMixedResult($regionNum, $okrugAbbr, $tikNum, $uikNum, $resultProjectCodes, $onlyControlRelTrue, true);
        $ofResult = $ofGateway->getMixedResult($regionNum, $okrugAbbr, $tikNum, $uikNum, ParserIKData_Model_Protocol::TYPE_OF, $onlyControlRelTrue, true);
        // $watchersResult = $protocolGateway->getMixedResult($regionNum, $okrugAbbr, null, 'OF', false, false, false, false);
    }
}

// формат ответа
$response = new stdClass();
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

    // если не только GN - явка некорректная
    if (in_array(PROJECT_GOLOS, $resultProjectCodes)) {
        $response->watchersData['AT'] = 0;
        $response->ofData['AT'] = 0;
    }
}


header('Content-Type: application/json');
print json_encode($response);