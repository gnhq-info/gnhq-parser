<?php
/**
 * creating necessary js files
 * @TODO: create
 */
require_once 'base.php';

$folder = 'mosmer';
ParserIKData_ServiceLocator::getInstance()
    ->setService('Gateway_Region', new ParserIKData_Gateway_Region_Mosmer())
    ->setService('Gateway_Tik', new ParserIKData_Gateway_Tik_Mosmer())
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Mosmer())
;

require_once( APPLICATION_DIR_ROOT . '/Elections/Actions/create-js.php');