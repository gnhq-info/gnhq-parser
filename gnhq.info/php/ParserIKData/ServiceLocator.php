<?php
class ParserIKData_ServiceLocator // implements Singleton
{
    private $_configFileName = 'services.ini';

    /**
     * @var Lib_Config_Interface
     */
    private $_config;

    /**
     * @var ParserIKData_ServiceLocator
     */
    private static $_instance = null;

    /**
     * @return ParserIKData_ServiceLocator
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            $item = new self();
            $item->_config = new Lib_Config_IniFile(array('iniFile' => $item->_getConfigFilePath()));
            self::$_instance = $item;
        }
        return self::$_instance;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function getWarehouse()
    {
        $className = 'ParserIKData_Warehouse_' . $this->_getConfig()->getValue('warehouse.type');
        if (!class_exists($className, true)) {
            throw new Exception ('Class '.$className. ' does not exist!');
        }
        $warehouse = new $className($this);
        if (!$warehouse instanceof ParserIKData_Warehouse_Interface) {
            throw new Exception ('Class '.$className. ' does not implement ParserIKData_Warehouse_Interface!');
        }
        return $warehouse;
    }

    /**
     * @return Lib_Db_MySql
     */
    public function getMySql()
    {
        $mysqlConf = new Lib_Db_Config($this->getMySqlConfig());
        $mysql = Lib_Db_MySql::getForConfig($mysqlConf);
        return $mysql;
    }

    /**
     * @return Lib_Config_Interface
     */
    public function getMySqlConfig()
    {
        return $this->getConfigForFile('mysql.ini');
    }

    /**
     * @param string $file
     * @return Lib_Config_Interface
     */
    public function getConfigForFile($file)
    {
        return new Lib_Config_IniFile(array('iniFile' => $this->_getConfigDirectory() . $file));
    }

    /**
     * @return Lib_Cache_Interface
     */
    public function getWebCache()
    {
        return ParserIKData_Cache_Web::factory();
    }

    /**
     * @return Lib_Cache_Interface
     */
    public function getGatewayCache()
    {
        return ParserIKData_Cache_Gateway::factory();
    }

    /**
     * @return Lib_Config_Interface
     */
    private function _getConfig()
    {
        return $this->_config;
    }

    /**
     * @return string
     */
    private function _getConfigFilePath()
    {
        return $this->_getConfigDirectory() . $this->_configFileName;
    }

    /**
     * @return string
     */
    private function _getConfigDirectory()
    {
        $dir = __DIR__;
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);
        $parts = explode(DIRECTORY_SEPARATOR, $dir);
        unset($parts[count($parts)-1]);
        $dir = implode(DIRECTORY_SEPARATOR, $parts);
        return $dir . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR;
    }
}