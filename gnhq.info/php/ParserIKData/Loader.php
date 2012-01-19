<?php
class ParseIKData_Loader
{
    private $_src;
    private $_useCache;
    private $_outputCharset = 'utf-8';

    public function __construct($src, $useCache = true)
    {
        $this->_src = $src;
        $this->_useCache = $useCache;
    }

    /**
     * @param string $src
     * @return ParseIKData_Loader
     */
    public function setSource($src)
    {
        $this->_src = $src;
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
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR;
        return $path . $this->_buildCacheKey();
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