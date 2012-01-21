<?php
set_time_limit(0);
include_once '../../Lib/String/Extracter.php';

include_once '../Config.php';
include_once '../ServiceLocator.php';

include_once '../SiteProcessor.php';
include_once '../Loader.php';
include_once '../Parser.php';
include_once '../DataMiner.php';

include_once '../Warehouse/Interface.php';
include_once '../Warehouse/Csv.php';

include_once '../Model.php';
include_once '../Model/Okrug.php';
include_once '../Model/TIK.php';
include_once '../Model/UIK.php';

/**
* @var ParserIKData_ServiceLocator
*/
$serviceLocator = ParserIKData_ServiceLocator::getInstance();