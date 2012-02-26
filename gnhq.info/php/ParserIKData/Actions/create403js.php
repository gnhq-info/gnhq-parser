<?php
include_once 'include.php';
$jsDir = rtrim(WEB_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;

switch ($argv[1])
{
    case 'violtypes':
        $violTypeFile = $jsDir . 'violtypes_data.js';
        $vtypeGateway = new ParserIKData_Gateway_ViolationType();
        $types = $vtypeGateway->setUseCache(false)->getAll('name');
        $violTypeJs = 'StaticData.ViolationTypes = {';
        $total = count($types);
        $i = 0;
        foreach ($types as $type) {
            $i++;
            /* @var $type ParserIKData_Model_ViolationType */
            $violTypeJs .= PHP_EOL . "\t'".$type->getMergedType()."': '".$type->getFullName() . ($i < $total ? "'," : "'");
        }
        $violTypeJs .= PHP_EOL . '};'.PHP_EOL;
        savejs($violTypeFile, $violTypeJs);
        break;

    case 'regions':
        $rFile = $jsDir . 'regions_data.js';
        $regionsGateway = new ParserIKData_Gateway_Region();
        $regions = $regionsGateway->getAll();
        $regionJs = 'StaticData.Regions = {';
        $total = count($regions);
        $i=0;
        foreach ($regions as $region) {
            $i++;
            /* @var $region ParserIKData_Model_Region */
            $regionJs .= PHP_EOL . "\t'".$region->getRegionNum()."': '".$region->getFullName() . ($i < $total ? "'," : "'");
        }
        $regionJs .= PHP_EOL . '};'.PHP_EOL;
        savejs($rFile, $regionJs);
        break;

    case 'watchers':
        $file = $jsDir . 'watchers_data.js';
        $js = 'StaticData.Watchers = {';
        $total = count($PROJECT_CONFIG);
        $i=0;
        foreach ($PROJECT_CONFIG as $projectCode => $data) {
            $i++;
            $js .= PHP_EOL . "\t'".$projectCode."': '".$data['Name'] . ($i < $total ? "'," : "'");
        }
        $js .= PHP_EOL . '};'.PHP_EOL;
        savejs($file, $js);
        break;

    case 'okrugs':
        $file = $jsDir . 'okrug_data.js';
        $js = 'StaticData.Okrugs = {'.PHP_EOL."\t77 : {".PHP_EOL;
        $oGateway = new ParserIKData_Gateway_Okrug();
        $okrugs = $oGateway->getAll();
        $total = count($okrugs);
        $i=0;
        foreach ($okrugs as $okrug) {
            $i++;
            /* @var $okrug ParserIKData_Model_Okrug */
            $js .= PHP_EOL . "\t\t'".$okrug->getAbbr()."': '".$okrug->getAbbr() . ($i < $total ? "'," : "'");
        }
        $js .= PHP_EOL . "\t}" . PHP_EOL . '};'.PHP_EOL;
        savejs($file, $js);
        break;

    case 'tiks':
        $file = $jsDir . 'tik_data.js';
        $js = 'StaticData.Tiks = [];'.PHP_EOL;
        $tGateway = new ParserIKData_Gateway_TIKRussia();
        $tiks = $tGateway->getAllByRegions();
        $curRegNum = 0;
        foreach ($tiks as $tik) {
            /* @var $tik ParserIKData_Model_TIKRussia */
            if ($curRegNum != $tik->getRegionNum()) {
                $js .= PHP_EOL . "\tStaticData.Tiks[".$tik->getRegionNum()."] = [];";
                $curRegNum = $tik->getRegionNum();
            }
            $js .= PHP_EOL . "\t\tStaticData.Tiks[".$tik->getRegionNum()."][".$tik->getTikNum()."] = \"".$tik->getFullName()."\";";
        }
        savejs($file, $js);
        break;

    default:
        break;
}

function savejs ($file, $js) {
    if (!file_exists($file)) {
        $f = fopen($file, "a+");
        if (!$f) {
            print 'Cant write to file:'.$file;
        }
        fclose($f);
    }
    file_put_contents($file, $js);
}
