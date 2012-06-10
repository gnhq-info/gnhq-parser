<?php
$dirParts = explode(DIRECTORY_SEPARATOR, trim(__DIR__, DIRECTORY_SEPARATOR));
array_pop($dirParts);
array_pop($dirParts);
array_pop($dirParts);
$pathToBasicInclude = implode(DIRECTORY_SEPARATOR, $dirParts);
unset($dirParts);
require_once $pathToBasicInclude . DIRECTORY_SEPARATOR. 'Actions' . DIRECTORY_SEPARATOR . 'include.php';
unset($pathToBasicInclude);
