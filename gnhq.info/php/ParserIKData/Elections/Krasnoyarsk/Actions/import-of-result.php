<?php
/**
 * import official results from cik site
 */
require_once 'base.php';

ParserIKData_ServiceLocator::getInstance()
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Krasnoyarsk())
    ->setService('Gateway_ProtocolOf', new ParserIKData_Gateway_Protocol_KrasnoyarskOf())
    ->setService('Site_CikUIK', new ParserIKData_Site_CikUIK_Krasnoyarsk());

require_once( APPLICATION_DIR_ROOT . '/Elections/Actions/import-of-result.php');

