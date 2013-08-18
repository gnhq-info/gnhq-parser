<?php
/**
 * action for (cron) tasks of importing protocol feeds from observers projects
 * @argv[1] - projectCode
 */
require_once('base.php');

ParserIKData_ServiceLocator::getInstance()
    ->setService('XmlProcessor_Protocol', new ParserIKData_XMLProcessor_Protocol_Mosobl(isset($argv[1]) ? $argv[1] : ''))
    ->setService('Gateway_Protocol', new ParserIKData_Gateway_Protocol_Mosobl());

require_once( APPLICATION_DIR_ROOT . '/Elections/Actions/import-proto.php');

