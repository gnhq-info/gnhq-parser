<?php
include_once('Watch412.php');
include_once('Protocol412.php');
include_once('Report412.php');
include_once('UIK.php');
include_once('TIK.php');
include_once('Okrug.php');

class ParserIKData_Gateway_Abstract
{
    private $_driver = null;

    /**
     * @return Lib_Db_MySql
     */
    final protected function _getDriver()
    {
        if ($this->_driver == null) {
            $iconf = ParserIKData_ServiceLocator::getInstance()->getMySqlConfig();
            $conf = new Lib_Db_Config($iconf);
            $this->_driver = Lib_Db_MySql::getForConfig($conf);
        }
        return $this->_driver;
    }

    /**
     * @param string $string
     */
    final protected function _escapeString($string)
    {
        return $this->_getDriver()->escapeString($string);
    }

    /**
     * @param resourse $result
     * @return array
     */
    final protected function _fetchResultToArray($result)
    {
        return $this->_getDriver()->fetchResultToArray($result);
    }
}