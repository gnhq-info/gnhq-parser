<?php
set_time_limit(0);

$dir = __DIR__;
$dirParts = explode(DIRECTORY_SEPARATOR, $dir);
unset($dirParts[count($dirParts)-1]);
unset($dirParts[count($dirParts)-1]);
define('BASE_INCLUDE_DIR', implode(DIRECTORY_SEPARATOR, $dirParts) . DIRECTORY_SEPARATOR );
unset($dir);
unset($dirParts);

define('APPLICATION_DIR_ROOT', BASE_INCLUDE_DIR . 'ParserIKData/');
define('LIB_DIR_ROOT', BASE_INCLUDE_DIR . 'Lib/');

require_once LIB_DIR_ROOT . 'ZendConfig.php';

include_once LIB_DIR_ROOT . 'String/Extracter.php';

include_once LIB_DIR_ROOT. 'Html/DataMiner.php';
include_once LIB_DIR_ROOT. 'Html/Loader.php';
include_once LIB_DIR_ROOT. 'Html/Parser.php';

include_once LIB_DIR_ROOT. 'Config/Interface.php';

include_once LIB_DIR_ROOT. 'Db/Config.php';
include_once LIB_DIR_ROOT. 'Db/MySql.php';

include_once LIB_DIR_ROOT. 'Cache/Interface.php';

include_once APPLICATION_DIR_ROOT . 'ServiceLocator.php';

include_once APPLICATION_DIR_ROOT . 'Site/Abstract.php';

include_once APPLICATION_DIR_ROOT . 'Warehouse/Interface.php';

include_once APPLICATION_DIR_ROOT . 'Model.php';

include_once APPLICATION_DIR_ROOT . 'Gateway/include.php';

include_once APPLICATION_DIR_ROOT . 'Cache/include.php';