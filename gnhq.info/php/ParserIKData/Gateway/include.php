<?php
include_once('Watch412.php');
include_once('Watch403.php');
include_once('Protocol412.php');
include_once('Protocol403.php');
include_once('Report412.php');
include_once('UIK.php');
include_once('TIK.php');
include_once('Okrug.php');
include_once('Region.php');
include_once('TIKRussia.php');
include_once('UIKRussia.php');
include_once('ViolationType.php');
include_once('Violation.php');
include_once('Twit.php');

class ParserIKData_Gateway_Abstract
{
    private $_driver = null;

    /**
     * @var ParserIKData_Cache_Gateway
     */
    private $_cache = null;

    /**
     * @var boolean
     */
    private $_useCache = false;


    /**
    * @param string $fileName
    * @param string $modelClass
    * @param string $where
    * @param string $limit
    * @param string $order
    * @return ParserIKData_Model[]
    */
    protected function _loadFromTable($tableName, $modelClass, $where = null, $limit = null, $order = null)
    {
        $dbRes = $this->_getDriver()->select('*', $tableName, $where, $limit, $order);
        $result = array();
        while ($arr = $this->_getDriver()->fetchResultToArray($dbRes)) {
            $result[] = $modelClass::fromArray($arr);
        }
        return $result;
    }

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


    /**
     * @return ParserIKData_Gateway_UIK
     */
    final protected function _getUikGateway()
    {
        return new ParserIKData_Gateway_UIK();
    }

    /**
     * @return ParserIKData_Gateway_Watch412
     */
    final protected function _getWatchGateway()
    {
        return new ParserIKData_Gateway_Watch412();
    }

    /**
     * @return ParserIKData_Gateway_TIK
     */
    final protected function _getTikGateway()
    {
        return new ParserIKData_Gateway_TIK();
    }

    /**
     * @return ParserIKData_Gateway_Protocol412
     */
    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol412();
    }

    /**
     * @return ParserIKData_Gateway_Report412
     */
    final protected function _getReportGateway()
    {
        return new ParserIKData_Gateway_Report412();
    }


    /**
     * @param string $class
     * @param string $method
     * @param array $args
     * @return boolean|mixed
     */
    final protected function _loadFromCache($class, $method, $args)
    {
        if (!$this->useCache()) {
            return false;
        }
        return $this->_getCache()->read($this->_buildCacheKey($class, $method, $args));
    }

    /**
     * @param string $class
     * @param string $method
     * @param array $args
     * @param mixed $result
     * @return boolean
     */
    final protected function _saveToCache($class, $method, $args, $result)
    {
        if (!$this->useCache()) {
            return false;
        }
        return $this->_getCache()->save($this->_buildCacheKey($class, $method, $args), $result);
    }

    /**
     * @return null|int
     */
    protected function _getCacheLifetime()
    {
        return null;
    }

    /**
     * @return Lib_Cache_Interface
     */
    private function _getCache()
    {
        if ($this->_cache === null) {
            $this->_cache = ParserIKData_ServiceLocator::getInstance()->getGatewayCache();
        }
        if ($this->_getCacheLifetime()) {
            $this->_cache->setLifetime($this->_getCacheLifetime());
        }
        return $this->_cache;
    }

    /**
     * @param string $class
     * @param string $function
     * @param array $args
     * @throws Exception
     * @return string
     */
    private function _buildCacheKey($class, $function, $args)
    {
        foreach ($args as $arg) {
            if (is_object($arg)) {
                throw new Exception('cant use cache for methods with object vars! ' .$method);
            }
        }
        return md5($class . $function . serialize($args));
    }

    /**
     * @return boolean
     */
    final public function setUseCache($val)
    {
        $this->_useCache = (bool)$val;
        return $this;
    }

    /**
     * @return boolean
     */
    final public function useCache()
    {
        return $this->_useCache;
    }
}