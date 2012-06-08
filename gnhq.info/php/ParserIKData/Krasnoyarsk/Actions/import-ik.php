<?php
/**
 * import information about tiks and uiks from CIK sajt
 */
require_once 'base.php';

switch ($argv[1])
{
    case 'loadTiks':
        $rGateway = new ParserIKData_Gateway_Region_Krasnoyarsk();
        $tGateway = new ParserIKData_Gateway_Tik_Krasnoyarsk();
        $regions = $rGateway->getAll();
        $parser = new ParserIKData_Site_Krasnoyarsk_IkData();

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