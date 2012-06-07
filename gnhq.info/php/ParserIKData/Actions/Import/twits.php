<?php
$dirParts = explode(DIRECTORY_SEPARATOR, trim(__DIR__, DIRECTORY_SEPARATOR));
array_pop($dirParts);
$pathToBasicInclude = implode(DIRECTORY_SEPARATOR, $dirParts);
unset($dirParts);
include_once $pathToBasicInclude . 'include.php';

$twitter = new ParserIKData_XMLProcessor_Twitter();
$twitter->import();