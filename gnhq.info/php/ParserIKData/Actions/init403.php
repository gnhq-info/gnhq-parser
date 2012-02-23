<?php
include_once 'include.php';

switch ($argv[1])
{
    case 'loadRegions':

        $parser = new ParserIKData_Site_CikMarch();
        $linkData = $parser->getRegionLinks();

        print (PHP_EOL . PHP_EOL . str_repeat('=', 40) . PHP_EOL . PHP_EOL);
        print ('Links loaded!' . PHP_EOL . PHP_EOL);
        $normData = array();
        foreach ($linkData as $k => $v) {
            $normData[_normalize($k)] = $v;
        }
        unset($linkData);

        $gateway = new ParserIKData_Gateway_Region();
        $gateway->removeAll();
        $data = file_get_contents('C:\temp\regions.txt');
        $lines = explode(PHP_EOL, $data);
        foreach ($lines as $line) {
            list($name, $num) = explode(',', $line);
            if (isset($normData[_normalize($name)])) {
                $link = $normData[_normalize($name)];
            } else {
                switch (true) {
                    case (mb_strpos($name, 'дыг') > 0):
                        $link = $normData[_normalize('Республика Адыгея (Адыгея)')];
                        break;
                    case ($name == 'Город Байконур (Республика Казахстан)'):
                        $link = $normData[_normalize('98 Город Байконур (Республика Казахстан)')];
                        break;
                    case ($name == 'Территория за пределами РФ'):
                        $link = $normData[_normalize('99 Территория за пределами РФ')];
                        break;
                    default:
                        break;
                }
            }
            $region = ParserIKData_Model_Region::createFromPageInfo($name, $link, array('num' => $num));
            $gateway->save($region);
        }
        break;

    case 'loadTiks':
        $rGateway = new ParserIKData_Gateway_Region();
        $tGateway = new ParserIKData_Gateway_TIKRussia();
        $regions = $rGateway->getAll();
        $parser = new ParserIKData_Site_CikMarch();

        $tGateway->removeAll();
        foreach ($regions as $region) {
            print ($region->getFullName() . PHP_EOL. str_repeat('=', 40) . PHP_EOL );
            $link = $region->getLink();
            if (!$link) {
                continue;
            }
            $data = $parser->getTIKLinks($link);
            foreach ($data as $name => $link) {
                list($tikNum, $tikName) = explode(' ', $name, 2);
                $tikR = ParserIKData_Model_TIKRussia::createFromPageInfo(
                    _normalize($tikName),
                    $link,
                    array('regionNum' => $region->getRegionNum(), 'tikNum' => intval($tikNum))
                );
                $tGateway->save($tikR);
            }
        }

        break;

     case 'loadUiks':
            $tGateway = new ParserIKData_Gateway_TIKRussia();
            $uGateway = new ParserIKData_Gateway_UIKRussia();
            $tiks = $tGateway->getAll();
            $parser = new ParserIKData_Site_CikMarch();

            $uGateway->removeAll();
            foreach ($tiks as $tik) {
                /* @var $tik ParserIKData_Model_TIKRussia */
                print ($tik->getFullName() . PHP_EOL. str_repeat('=', 40) . PHP_EOL );
                $link = $tik->getLink();
                if (!$link) {
                    continue;
                }
                $data = $parser->getTIKLinks($link);
                foreach ($data as $name => $link) {
                    list($uikNum, $uikName) = explode(' ', trim($name), 2);
                    $uikR = ParserIKData_Model_UIKRussia::createFromPageInfo(
                        '',
                        $link,
                        array('regionNum' => $tik->getRegionNum(), 'tikNum' => intval($tik->getTikNum()), 'uikNum' => intval($uikNum))
                        );
                    $uGateway->save($uikR);
                }
            }

            break;

    default:
        break;
}

function _normalize($str)
{
    return trim(mb_strtolower($str, mb_detect_encoding($str)));
}