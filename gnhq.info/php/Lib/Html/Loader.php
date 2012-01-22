<?php
class Lib_Html_Loader
{
    private $_src;
    private $_useCache;
    private $_outputCharset = 'utf-8';
    private $_cacheDir;

    public function __construct($src, $useCache = true, $outputCharset = null)
    {
        $this->_src = $src;
        $this->_useCache = $useCache;
        if ($outputCharset) {
            $this->_outputCharset = $outputCharset;
        }
    }

    /**
     * @param string $dir
     * @return Lib_Html_Loader
     */
    public function setCacheDir($dir)
    {
        $this->_cacheDir = $dir;
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
        if ($result) {
            $writeResult = file_put_contents($this->_getCacheFilename(), $result);
            return $writeResult;
        } else {
            return false;
        }
    }

    /**
     * load page from cache
     * @return string|boolean
     */
    private function _loadFromCache()
    {
        $cacheFileName = $this->_getCacheFilename();
        if (file_exists($cacheFileName)) {
            return file_get_contents($cacheFileName);
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    private function _getCacheFilename()
    {
        return rtrim($this->_cacheDir, DIRECTORY_SEPARATOR) .  DIRECTORY_SEPARATOR . $this->_buildCacheKey();
    }

    private function _buildCacheKey()
    {
        return md5($this->_src);
    }

    private function _encode($string)
    {
        return iconv(mb_detect_encoding($string), $this->_outputCharset . '//IGNORE', $string);
    }
}