<?php
class Lib_Db_Config
{
    private $_data = array();

    public function getUser() {
        return $this->_data['user'];
    }

    public function getPwd() {
        return $this->_data['pwd'];
    }

    public function getHost() {
        return $this->_data['host'];
    }

    public function getCharset() {
        return $this->_data['charset'];
    }

    public function getDefaultDatabase() {
        return $this->_data['db'];
    }


    /**
     * @param Lib_Config_Interface $config
     */
    public function __construct($config)
    {
        if (!$config->getValue('host') || !$config->getValue('user') || !$config->getValue('password')) {
            throw new Exception('DB connection parameters are not full');
        }
        $this->_data['host']     = $config->getValue('host');
        $this->_data['user']     = $config->getValue('user');
        $this->_data['pwd']      = $config->getValue('password');
        if ($config->getValue('charset')) {
            $this->_data['charset']  = $config->getValue('charset');
        } else {
            $this->_data['charset'] = 'UTF-8';
        }
        if ($config->getValue('db')) {
            $this->_data['db'] = $config->getValue('db');
        } else {
            $this->_data['db'] = null;
        }
    }
}