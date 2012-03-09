<?php
// loading official results from cik site
include_once 'include.php';

$f = fopen(DATA_PATH . 'results_40312csv.csv', 'r');

if (!$f) {
    die('cant open file');
}
$cnt=0;
$gateway = new ParserIKData_Gateway_Protocol403Offile();
while ($arr = fgetcsv($f, 0, ';', '"')) {
    $cnt++;
    if ($cnt == 1 || !$arr[1]) {
        continue;
    }

    $proto = ParserIKData_Model_Protocol403::create();
    $proto->setResultType(ParserIKData_Model_Protocol403::TYPE_OF);
    $data = array();
    for ($i = 1; $i < ParserIKData_Model_Protocol403::LINE_AMOUNT; $i++) {
        $data[$i] = 0;
    }
    $data[ParserIKData_Model_Protocol403::INDEX_VZ] = $arr[4];
    $data[ParserIKData_Model_Protocol403::INDEX_GZ] = $arr[5];
    $data[ParserIKData_Model_Protocol403::INDEX_SM] = $arr[6];
    $data[ParserIKData_Model_Protocol403::INDEX_MP] = $arr[7];
    $data[ParserIKData_Model_Protocol403::INDEX_VP] = $arr[8];
    $data[ParserIKData_Model_Protocol403::INDEX_SPOILED] = $arr[19];
    $data[ParserIKData_Model_Protocol403::INDEX_TOTAL_VOTED] = $arr[10];
    $data[ParserIKData_Model_Protocol403::INDEX_TOTAL] = $arr[11];
    $proto->setData($data);
    $proto->setProjectId(0);
    $proto->setIkFullName($arr[0]*ParserIKData_Model_UIKRussia::MODULE + $arr[1]);
    $proto->setClaimCount(0);
    $proto->setUpdateTime(date('Y-m-d H:i:s'));
    print($proto->getIkFullName() . ' processed' . PHP_EOL);
    $gateway->insert($proto);
}

fclose($f);

print ('official results loaded');
