<?php
/**
 * creating necessary js files
 * @TODO: create
 */
require_once 'base.php';

$folder = 'mosobl';
ParserIKData_ServiceLocator::getInstance()
    ->setService('Gateway_Region', new ParserIKData_Gateway_Region_Mosobl())
    ->setService('Gateway_Tik', new ParserIKData_Gateway_Tik_Mosobl())
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Mosobl())
;

require_once( APPLICATION_DIR_ROOT . '/Elections/Actions/create-js.php');