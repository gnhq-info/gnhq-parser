<?php
include_once 'include.php';

$projectCodes = array('GN');

$average = new ParserIKData_Helper_403Average($projectCodes, true, true);

$average->calcProjectResults();
$average->calcOfResults();
var_dump($average->getProjectDiagramData());
var_dump($average->getProjectUikCount());
var_dump($average->getOfDiagramData());
var_dump($average->getOfUikCount());
