<?php
$jsDir = rtrim(WEB_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR .
	'elections' . DIRECTORY_SEPARATOR .
    $folder . DIRECTORY_SEPARATOR .
	'js' . DIRECTORY_SEPARATOR;

switch ($argv[1])
{
    case 'region':
        $rFile = $jsDir . 'regions_data.js';
        $regionsGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Region');
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

    case 'tik':
        $file = $jsDir . 'tik_data.js';
        $js = 'StaticData.Tiks = [];'.PHP_EOL;
        $js .= 'StaticData.TiksOrder = [];'.PHP_EOL;
        $tGateway = ParserIKData_ServiceLocator::getInstance()->getService('Gateway_Tik');
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
