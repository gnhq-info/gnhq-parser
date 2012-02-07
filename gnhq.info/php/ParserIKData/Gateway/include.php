<?php
include_once('Watch412.php');
include_once('Protocol412.php');
include_once('UIK.php');
include_once('TIK.php');
include_once('Okrug.php');

class ParserIKData_Gateway_Abstract
{
    /**
     * @return Lib_Db_MySql
     */
    protected function _getDriver()
    {
        $iconf = ParserIKData_ServiceLocator::getInstance()->getMySqlConfig();
        $conf = new Lib_Db_Config($iconf);
        return Lib_Db_MySql::getForConfig($conf);
    }
}