<?php
/**
 * import information about tiks and uiks from CIK sajt
 */
require_once 'base.php';

ParserIKData_ServiceLocator::getInstance()
    ->setService('Config_Region', ParserIKData_ServiceLocator::getInstance()->getConfigForFile('mosmer.ini'))
    ->setService('Gateway_Region', new ParserIKData_Gateway_Region_Mosmer())
    ->setService('Gateway_Tik', new ParserIKData_Gateway_Tik_Mosmer())
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Mosmer())
    ->setService('Site_Ik', new ParserIKData_Site_Mosmer_IkData())
;

require_once( APPLICATION_DIR_ROOT . '/Elections/Actions/import-ik.php');
