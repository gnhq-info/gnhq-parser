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


    /**
     * @param Lib_Config_Interface $config
     */
    public function __construct($config)
    {
        if (!$config->getValue('host') || !$config->getValue('user') || !$config->getValue('password')) {
            throw new Exception('DB connection parameters are not full');
        }
        $this->_data['host'] = $config->getValue('host');
        $this->_data['user'] = $config->getValue('user');
        $this->_data['pwd']  = $config->getValue('password');
    }
}