<?php
/**
 * action for (cron) tasks of importing violation feeds from various sources (various observers projects)
 * @argv[1] - projectCode
 */
require_once('base.php');

ParserIKData_ServiceLocator::getInstance()
    ->setService('XmlProcessor_Violation', new ParserIKData_XMLProcessor_Violation_Mosobl(isset($argv[1]) ? $argv[1] : ''))
    ->setService('Gateway_Violation', new ParserIKData_Gateway_Violation_Mosobl());

require_once( APPLICATION_DIR_ROOT . '/Elections/Actions/import-viol-json.php');
