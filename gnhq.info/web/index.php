<?php
$dirParts = explode(DIRECTORY_SEPARATOR, __DIR__);
unset($dirParts[count($dirParts)-1]);
$dirParts[] = 'php';
$dirParts[] = 'ParserIKData';
$dirParts[] = 'Actions';
$dirParts[] = 'include.php';
$baseIncludeFilePath = implode(DIRECTORY_SEPARATOR, $dirParts);

echo 'hahaha';