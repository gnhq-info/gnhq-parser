<?php
require_once('Basic.php');

interface Lib_Cache_Interface
{
    public function read($key);

    public function save($key, $value);
}