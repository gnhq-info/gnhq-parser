<?php
class Lib_Html_Loader
{
    private $_src;
    private $_useCache;
    private $_outputCharset = 'UTF-8';
    private $_inputEncoding = null;

    /**
     * @var Lib_Cache_Interface
     */
    private $_cache = null;

    public function __construct($src, $useCache = true, $outputCharset = null)
    {
        $this->_src = $src;
        $this->_useCache = $useCache;
        if ($outputCharset) {
            $this->_outputCharset = $outputCharset;
        }
    }

    public function setCache($cache)
    {
        $this->_cache = $cache;
        return $this;
    }


    /**
     * @param string $enc
     * @return Lib_Html_Loader
     */
    public function setInputEncoding($enc)
    {
        $this->_inputEncoding = $enc;
        return $this;
    }

    /**
     * @param string $src
     * @return Lib_Html_Loader
     */
    public function setSource($src)
    {
        $this->_src = $src;
        return $this;
    }

    /**
     * @param boolean $useCache
     * @return Lib_Html_Loader
     */
    public function setUseCache($useCache)
    {
        $this->_useCache = (bool)$useCache;
        return $this;
    }

    /**
     * @param string $outputCharset
     * @return Lib_Html_Loader
     */
    public function setOutputCharset($outputCharset)
    {
        $this->_outputCharset = $outputCharset;
        return $this;
    }

    /**
     * @return string
     */
    public function load()
    {
        if ($this->_useCache && ($result = $this->_loadFromCache())) {
            return $result;
        } else {
            $result = $this->_encode($this->_loadFromSource());
            $this->_writeToCache($result);
            return $result;
        }
    }

    /**
     * @return string
     */
    private function _loadFromSource()
    {
        return file_get_contents($this->_src);
    }


    /**
     * @param string $result
     * @return number|boolean
     */
    private function _writeToCache($result)
    {
        if (!$this->_cache) {
            return false;
        }
        $this->_cache->save($this->_buildCacheKey(), $result);
    }

    /**
     * load page from cache
     * @return string|boolean
     */
    private function _loadFromCache()
    {
        if (!$this->_cache) {
            return false;
        }
        return $this->_cache->read($this->_buildCacheKey());
    }


    private function _buildCacheKey()
    {
        return md5($this->_src);
    }

    private function _encode($string)
    {
        $enc =  $this->_inputEncoding ? $this->_inputEncoding : mb_detect_encoding($string);
        if ($enc === $this->_outputCharset) {
            return $string;
        } else {
            return iconv($enc, $this->_outputCharset . '//IGNORE', $string);
        }
    }
}