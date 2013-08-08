<?php
include_once 'include.php';
$jsDir = rtrim(WEB_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;

switch ($argv[1])
{
    case 'violtypes':
        $violTypeFile = $jsDir . 'violtypes_data.js';
        $vtypeGateway = new ParserIKData_Gateway_ViolationType();
        $types = $vtypeGateway->setUseCache(false)->getAllDistinct('name');
        $violTypeJs = 'StaticData.ViolationTypes = [];' . PHP_EOL;
        $violTypeJs .= 'StaticData.ViolationTypeGroups = [];' . PHP_EOL;
        $violTypeJs .= 'StaticData.ViolationTypeGroupData = {};' . PHP_EOL;
        $i = 0;
        $grps = array();
        foreach ($types as $type) {
            $i++;
            /* @var $type ParserIKData_Model_ViolationType */
            $violTypeJs .= "StaticData.ViolationTypes[".$type->getMergedType()."] = '".$type->getFullName() . "';".PHP_EOL;
            $violTypeJs .= "StaticData.ViolationTypeGroups[".$type->getMergedType()."] = ".$type->getGroup() . ";".PHP_EOL;
            if ($type->getFullName()) {
                $grps[$type->getGroup()] = $type->getFullName();
            }
        }
        foreach ($grps as $i => $n) {
            $violTypeJs .= "StaticData.ViolationTypeGroupData[".$i."] = '".$n."';".PHP_EOL;
        }



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
        $js .= 'StaticData.TiksOrder = [];'.PHP_EOL;
        $tGateway = new ParserIKData_Gateway_TIKRussia();
        $tiks = $tGateway->getAllByRegions();
        $curRegNum = 0;
        $regInd = 0;
        foreach ($tiks as $tik) {
            /* @var $tik ParserIKData_Model_TIKRussia */
            if ($curRegNum != $tik->getRegionNum()) {
                $js .= PHP_EOL . "\tStaticData.Tiks[".$tik->getRegionNum()."] = [];";
                $js .= PHP_EOL . "\tStaticData.TiksOrder[".$tik->getRegionNum()."] = [];";
                $curRegNum = $tik->getRegionNum();
                $regInd = 0;
            }
            $js .= PHP_EOL . "\t\tStaticData.Tiks[".$tik->getRegionNum()."][".$tik->getTikNum()."] = \"".$tik->getFullName()."\";";
            $js .= PHP_EOL . "\t\tStaticData.TiksOrder[".$tik->getRegionNum()."][".$regInd."] = ".$tik->getTikNum().";";
            $regInd++;
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
