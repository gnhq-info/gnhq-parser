<?php
abstract class Lib_Cache_Basic implements Lib_Cache_Interface
{
    /**
     * @var Zend_Cache_Core
     */
    private $_cache = null;


    /**
     * @param string $className
     * @return Lib_Cache_Interface
     */
    public static function factory($className = null)
    {
        if (empty($className)) {
            $className = get_called_class();
        }
        if (!@class_exists($className)) {
            throw new Exception('Класс не найден '.$className);
        }
        $item = new $className();
        if (!$item instanceof Lib_Cache_Basic) {
            throw new Exception('Класс '.$className.' не наследует Lib_Cache_Basic');
        }
        $item->_initCacheDriver();
        return $item;
    }

    public function read($key)
    {
        return $this->_cache->load($key);
    }

    public function save($key, $value)
    {
        return $this->_cache->save($value, $key);
    }


    final protected function _initCacheDriver()
    {
        require_once('Zend/Cache.php');
        $this->_cache = Zend_Cache::factory($this->_getFrontEnd(), $this->_getBackEnd(), $this->_getFrontendOptions(), $this->_getBackendOptions());
    }

    protected function _getFrontEnd()
    {
        return 'Core';
    }

    protected function _getBackEnd()
    {
        return 'File';
    }

    protected function _getFrontendOptions()
    {
        return array('lifetime' => 86400, 'automatic_serialization' => true);
    }

    protected function _getBackendOptions()
    {
        return array(
        	'hashed_directory_level'  => 2,
        	'automatic_serialization' => true,
        	'hashed_directory_umask'  => 0700,
        	'cache_dir'               => ''
        );
    }


    final protected function __construct()
    {
    }
}