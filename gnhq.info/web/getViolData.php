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

$tikNum = isset($_GET['TIKNum']) ? $_GET['TIKNum'] : null;
if ($tikNum === '') {
    $tikNum = null;
} else {
    $tikNum = intval($tikNum);
}



/* далее все входные данные очищены */
$vGateway = new ParserIKData_Gateway_Violation();
$count = $vGateway->count($projectCode, $mergedTypeId, $regionNum, $tikNum);
$vshort = $vGateway->short($projectCode, $mergedTypeId, $regionNum, $tikNum);
foreach ($vshort as $k => $viol) {
    $vshort[$k] = $viol->getParams();
}
$tikCount = $vGateway->tikCount($projectCode, $mergedTypeId, $regionNum, $tikNum);

// формат ответа

$response = new stdClass();
$response->count = $count;
$response->regionNum = $regionNum;
$response->vshort = $vshort;
$response->tikCount = $tikCount;


header('Content-Type: application/json');
print json_encode($response);