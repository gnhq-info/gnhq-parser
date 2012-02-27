<?php
include_once 'include.php';

$twitter = new ParserIKData_XMLProcessor_Twitter();
$twitter->import();
$twitGateway = new ParserIKData_Gateway_Twit();
$twits = $twitGateway->getAll(5);
foreach ($twits as $twit) {
    /* @var $twit ParserIKData_Model_Twit */
    print ($twit->getTime() . ': ' . $twit->getFullName() . PHP_EOL);
}