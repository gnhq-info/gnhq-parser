<?php
/**
 * import official results from cik site
 */
require_once 'base.php';

ParserIKData_ServiceLocator::getInstance()
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Mosobl())
    ->setService('Gateway_ProtocolOf', new ParserIKData_Gateway_Protocol_MosoblOf())
    ->setService('Site_CikUIK', new ParserIKData_Site_CikUIK_Mosobl());

require_once( APPLICATION_DIR_ROOT . '/Elections/Actions/import-of-result.php');

