<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Base.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'IniFile.php');

interface Lib_Config_Interface
{
    /**
     * @param string $key
     */
    public function getValue($key);

    /**
     * @param string $key
     */
    public function getArrayValue($key);
}