<?php
$sampleName = 'krasnoyarsk';
clearstatcache(true);
$fname = APPLICATION_DIR_ROOT . 'Actions' . DIRECTORY_SEPARATOR . 'include.php';
$content = file_get_contents($fname);

$sampleStr = 'require_once APPLICATION_DIR_ROOT . \'Elections/'.ucfirst($sampleName).'/include.php\';';
$newStr = 'require_once APPLICATION_DIR_ROOT . \'Elections/'.ucfirst($electionName).'/include.php\';';

if (strpos($content, $newStr) === false) {
    $content = str_replace($sampleStr,  $newStr . PHP_EOL . PHP_EOL . $sampleStr, $content);
    file_put_contents($fname, $content);
}


$fname = WEB_DIR . 'elections.php';
$content = file_get_contents($fname);

$sampleStr = "'" . $sampleName  . "' => '" . $sampleName . "'";
$newStr = "'" . $electionName  . "' => '" . $electionName . "'";


if (strpos($content, $newStr) === false) {
    $content = str_replace($sampleStr,  $newStr . "," . PHP_EOL . "\t\t\t" . $sampleStr, $content);
    file_put_contents($fname, $content);
}