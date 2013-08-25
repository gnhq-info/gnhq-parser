<?php
/**
 * import information about tiks and uiks from CIK sajt
 */
require_once 'base.php';

ParserIKData_ServiceLocator::getInstance()
    ->setService('Config_Region', ParserIKData_ServiceLocator::getInstance()->getConfigForFile('krasnoyarsk.ini'))
    ->setService('Gateway_Region', new ParserIKData_Gateway_Region_Krasnoyarsk())
    ->setService('Gateway_Tik', new ParserIKData_Gateway_Tik_Krasnoyarsk())
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Krasnoyarsk())
    ->setService('Site_Ik', new ParserIKData_Site_Krasnoyarsk_IkData())
;

require_once( APPLICATION_DIR_ROOT . '/Elections/Actions/import-ik.php');
