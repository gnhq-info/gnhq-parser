<?php
set_time_limit(0);
define('BASE_INCLUDE_DIR', 'C:\git\gnhq.info\gnhq\gnhq.info\php\\');
define('APPLICATION_DIR_ROOT', BASE_INCLUDE_DIR . 'ParserIKData\\');
define('LIB_DIR_ROOT', BASE_INCLUDE_DIR . 'Lib\\');

include_once LIB_DIR_ROOT . 'String/Extracter.php';

include_once LIB_DIR_ROOT. 'Html/DataMiner.php';
include_once LIB_DIR_ROOT. 'Html/Loader.php';
include_once LIB_DIR_ROOT. 'Html/Parser.php';

include_once LIB_DIR_ROOT. 'Config/Interface.php';

include_once LIB_DIR_ROOT. 'Db/Config.php';
include_once LIB_DIR_ROOT. 'Db/MySql.php';

include_once APPLICATION_DIR_ROOT . 'ServiceLocator.php';

include_once APPLICATION_DIR_ROOT . 'Site/Abstract.php';

include_once APPLICATION_DIR_ROOT . 'Warehouse/Interface.php';

include_once APPLICATION_DIR_ROOT . 'Model.php';
