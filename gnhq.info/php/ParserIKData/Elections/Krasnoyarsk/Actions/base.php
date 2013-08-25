<?php
$dirParts = explode(DIRECTORY_SEPARATOR, rtrim(__DIR__, DIRECTORY_SEPARATOR));
array_pop($dirParts);
require_once (implode(DIRECTORY_SEPARATOR, $dirParts) . DIRECTORY_SEPARATOR . 'Config.php');
array_pop($dirParts);
array_pop($dirParts);
$pathToBasicInclude = implode(DIRECTORY_SEPARATOR, $dirParts);
unset($dirParts);
require_once ($pathToBasicInclude . DIRECTORY_SEPARATOR. 'Actions' . DIRECTORY_SEPARATOR . 'include.php');
unset($pathToBasicInclude);
