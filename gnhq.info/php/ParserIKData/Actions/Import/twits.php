<?php
$dirParts = explode(DIRECTORY_SEPARATOR, rtrim(__DIR__, DIRECTORY_SEPARATOR));
array_pop($dirParts);
$pathToBasicInclude = implode(DIRECTORY_SEPARATOR, $dirParts);
unset($dirParts);
require_once $pathToBasicInclude . DIRECTORY_SEPARATOR . 'include.php';

$twitter = new ParserIKData_XMLProcessor_Twitter();
$twitter->import();