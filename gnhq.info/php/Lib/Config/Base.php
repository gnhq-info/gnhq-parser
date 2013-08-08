<?php
abstract class Lib_Config_Base implements Lib_Config_Interface
{

    private $_properties = array();

    /**
     * @param array() $params
     */
    abstract protected function _loadPropertiesFromSource($params);

    /**
    * @param string $key
    * @return string|null
    */
    public function getValue($key)
    {
        if (array_key_exists($key, $this->_properties)) {
            return $this->_properties[$key];
        } else {
            return null;
        }
    }

    /**
     * @param string $key
     * @return array
     */
    public function getArrayValue($key)
    {
        $val = $this->getValue($key);
        $var = trim($val, '[]');
        return explode(';', $var);
    }

    /**
     * @param array $params
     */
    final public function __construct($params)
    {
        $this->_properties = $this->_loadPropertiesFromSource($params);
    }
}