<?php
include_once ('Base.php');
include_once('IniFile.php');

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