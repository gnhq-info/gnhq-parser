<?php
/**
 *  loading official moscow municipal results from cik site
 */

include_once 'include.php';

$processor = new ParserIKData_Site_MRes403();
$mosOkrGateway = new ParserIKData_Gateway_MoscowOkrug();
/*
 * loading okrugs
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

// end of loading okrugs
*/


/*
$oResults = array();
foreach ($olinks as $tikName => $tikOkrugs) {
    foreach ($tikOkrugs as $oName => $oLink) {
        $okrugResults = $processor->getOkrugResults($oLink);
        $oResults[$tikName][$oName] = $okrugResults;
    }
}
*/

$mosOkrugs = $mosOkrGateway->getAllById();
var_dump($mosOkrugs);die();

$oCandidats = array();
$maxPerOkrug = 0;
$maxDescr = '';
foreach ($olinks as $tikName => $tikOkrugs) {
    foreach ($tikOkrugs as $oName => $oLink) {
        $okrugCandidats = $processor->getOkrugCandidats($oLink);
        if (count($okrugCandidats) > $maxPerOkrug) {
            $maxPerOkrug = count($okrugCandidats);
            $maxDescr = $tikName  . '-' . $oName;
        }
        $oCandidats[$tikName][$oName] = $okrugCandidats;
    }
}


print ('official results loaded');
