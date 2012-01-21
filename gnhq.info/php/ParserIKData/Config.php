<?php
class ParserIKData_Config
{
    private $_properties;

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

    public function __construct($file)
    {
        if (!file_exists($file)) {
            throw new Exception('No config file '.$file);
        }
        $this->_properties = parse_ini_file($file);
        if (!is_array($this->_properties)) {
            throw new Exception('Bad config file ' .$file);
        }
    }
}