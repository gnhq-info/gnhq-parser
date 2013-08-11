<?php
set_time_limit(0);

date_default_timezone_set('Etc/GMT-4');

$dir = __DIR__;
$dirParts = explode(DIRECTORY_SEPARATOR, $dir);
unset($dirParts[count($dirParts)-1]);
unset($dirParts[count($dirParts)-1]);
define('BASE_INCLUDE_DIR', implode(DIRECTORY_SEPARATOR, $dirParts) . DIRECTORY_SEPARATOR );
unset($dirParts[count($dirParts)-1]);
define('WEB_DIR', implode(DIRECTORY_SEPARATOR, $dirParts) . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR );
unset($dir);
unset($dirParts);

define('APPLICATION_DIR_ROOT', BASE_INCLUDE_DIR . 'ParserIKData' . DIRECTORY_SEPARATOR);
define('LIB_DIR_ROOT', BASE_INCLUDE_DIR . 'Lib' . DIRECTORY_SEPARATOR);
define('DATA_PATH', APPLICATION_DIR_ROOT . 'Data' . DIRECTORY_SEPARATOR);
define('INI_CONFIG_PATH', BASE_INCLUDE_DIR . 'Config');

require_once APPLICATION_DIR_ROOT . 'Const.php';

require_once LIB_DIR_ROOT . 'ZendConfig.php';

require_once LIB_DIR_ROOT . 'String/Extracter.php';

require_once LIB_DIR_ROOT. 'Html/DataMiner.php';
require_once LIB_DIR_ROOT. 'Html/Loader.php';
require_once LIB_DIR_ROOT. 'Html/Parser.php';

require_once LIB_DIR_ROOT. 'Config/Interface.php';

require_once LIB_DIR_ROOT. 'Db/Config.php';
require_once LIB_DIR_ROOT. 'Db/MySql.php';

require_once LIB_DIR_ROOT. 'Cache/Interface.php';

require_once APPLICATION_DIR_ROOT . 'ServiceLocator.php';

require_once APPLICATION_DIR_ROOT . 'Site/Abstract.php';

require_once APPLICATION_DIR_ROOT . 'XML/Abstract.php';

require_once APPLICATION_DIR_ROOT . 'Warehouse/Interface.php';

require_once APPLICATION_DIR_ROOT . 'Model.php';

require_once APPLICATION_DIR_ROOT . 'Gateway/include.php';

require_once APPLICATION_DIR_ROOT . 'Cache/include.php';

require_once APPLICATION_DIR_ROOT . 'Elections/President12/include.php';

require_once APPLICATION_DIR_ROOT . 'Elections/Omsk/include.php';

require_once APPLICATION_DIR_ROOT . 'Elections/Krasnoyarsk/include.php';

if (!isset($PROJECT_CONFIG)) {
    require_once APPLICATION_DIR_ROOT . 'ProjectData.php';
}