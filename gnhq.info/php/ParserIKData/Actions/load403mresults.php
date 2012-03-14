<?php
/**
 *  loading official moscow municipal results from cik site
 */

include_once 'include.php';

$processor = new ParserIKData_Site_MRes403();
$links = $processor->getGeneralTikLinks();

foreach ($links as $text => $link) {
    $links[$text] = $processor->getResultLink($link);
}

$olinks = array();
foreach ($links as $text => $rlink) {
    $olinks[$text] = $processor->getOkrugLinks($rlink);
}

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

var_dump($maxPerOkrug);
var_dump($maxDescr);



print ('official results loaded');
