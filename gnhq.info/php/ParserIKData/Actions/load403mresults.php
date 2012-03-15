<?php
/**
 *  loading official moscow municipal results from cik site
 */

include_once 'include.php';

$processor       = new ParserIKData_Site_MRes403();
$mosOkrGateway   = new ParserIKData_Gateway_MoscowOkrug();
$mosCandGateway  = new ParserIKData_Gateway_MoscowCand();
$mosProtoGateway = new ParserIKData_Gateway_MoscowProtocol403();
/*
 * загрузка округов
 *
 *
$tikRGateway = new ParserIKData_Gateway_TIKRussia();
$moscowTiks = $tikRGateway->getForRegion(ParserIKData_Model_Region::MOSCOW);
$tiksNumByNormalizedNames = array();
$tiksByNums = array();
foreach ($moscowTiks as $mTik) {
    /* @var $mTik ParserIKData_Model_TIKRussia * /
    $tiksNumByNormalizedNames[ParserIKData_Model_TIK::normalizeName($mTik->getFullName())] = $mTik->getTikNum();
    $tiksByNums[$mTik->getTikNum()] = $mTik;
}

$processor = new ParserIKData_Site_MRes403();
$links = $processor->getGeneralTikLinks();
foreach ($links as $text => $link) {
    unset($links[$text]);
    $links[$tiksNumByNormalizedNames[ParserIKData_Model_TIK::normalizeName($text)]] = $processor->getResultLink($link);
}

$olinks = array();
foreach ($links as $text => $rlink) {
    $olinks[$text] = $processor->getOkrugLinks($rlink);
}


$ocnt = 1;
foreach ($olinks as $tikName => $tikOkrugs) {
    foreach ($tikOkrugs as $oName => $oLink) {
        if (mb_strpos(mb_strtolower($oName, 'UTF-8'), 'одно') !== false ) {
            $magnitude = 1;
        } else {
            $magnitude = 0;
        }
        ParserIKData_Model_MoscowOkrug::createFromPageInfo($oName, $oLink, array('id' => $ocnt, 'tikNum' => $tikName, 'magnitude' => $magnitude));
        $ocnt++;
    }
}
$mosOkrugs = ParserIKData_Model_MoscowOkrug::getAllOBjects();
foreach ($mosOkrugs as $id => $mosOkr) {
    $mosOkrGateway->save($mosOkr);
}

// \загрузка округов
*/


$mosOkrugs = $mosOkrGateway->getAllById();
$oResults = array();
foreach ($mosOkrugs as $id => $mosOkr) {
    /* @var $mosOkr ParserIKData_Model_MoscowOkrug */
    $okrugResults = $processor->getOkrugResults($mosOkr->getLink());
    foreach ($okrugResults as $uik => $result) {
         $proto = ParserIKData_Model_MoscowProtocol403::create();
         $proto->setOkrId($mosOkr->getId());
         $proto->setUikNum(str_replace('УИК №', '', $uik));
         $proto->setData($result);
         $mosProtoGateway->insert($proto);
    }
}
print('loaded!');


/*
 * загрузка кандидатов
 * /
foreach ($mosOkrugs as $id => $mosOkr) {
    /* @var $mosOkr ParserIKData_Model_MoscowOkrug * /
    $okrugCandidats = $processor->getOkrugCandidats($mosOkr->getLink());
    foreach ($okrugCandidats as $num => $name) {
        $cand = ParserIKData_Model_MoscowCand::createFromPageInfo(
            $name, '', array('okrId' => $mosOkr->getId(), 'num' => $num, 'isBlacklist' => 0)
        );
        $mosCandGateway->save($cand);
    }
}
print ('candidats loaded');
*/