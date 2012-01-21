<?php
class ParserIKData_ServiceLocator
{
    private $_configFileName = 'services.ini';

    /**
     * @var ParserIKData_Config
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
            $item->_config = new ParserIKData_Config($item->_getConfigFilePath());
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
        $warehouse = new $className;
        if (!$warehouse instanceof ParserIKData_Warehouse_Interface) {
            throw new Exception ('Class '.$className. ' does not implement ParserIKData_Warehouse_Interface!');
        }
        return $warehouse;
    }

    /**
     * @param string $file
     * @return ParserIKData_Config
     */
    public function getConfigForFile($file)
    {
        return new ParserIKData_Config($this->_getConfigDirectory() . $file);
    }

    /**
     * @return ParserIKData_Config
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