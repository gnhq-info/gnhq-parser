<?php
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'Cik.php');
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'CikMarch.php');
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'CikUIK.php');
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'Mosgor.php');
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'Res412.php');
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'MRes403.php');
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'Gn412.php');
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'KartaNarushenij.php');

abstract class ParserIKData_Site_Abstract
{
    /**
    * @var Lib_Html_Parser
    */
    private $_parser = null;
    /**
     * @var Lib_Html_DataMiner
     */
    private $_miner = null;

    /**
     * @var Lib_Html_Loader
     */
    private $_loader = null;

    /**
     * @return string
     */
    abstract protected function _getConfigFileName();



    /**
    * @return Ambigous <string, NULL>
    */
    final protected function _getSite()
    {
        return $this->_getCValue('site');
    }



    /**
     * @param string $key
     * @return string|null
     */
    final protected function _getCValue($key)
    {
        return $this->_getConfig()->getValue($key);
    }

    /**
     * @param string $key
     * @return array|null
     */
    final protected function _getCArray($key)
    {
        return $this->_getConfig()->getArrayValue($key);
    }


    /**
    * @param string $key
    * @param string $string
    * @return string
    */
    final protected function _excludeCArray($key, $string)
    {
        $array = $this->_getCArray($key);
        if (!$array) {
            return $string;
        }
        $replace = array_fill(0, count($array), '');
        return str_replace($array, $replace, $string);
    }

    /**
    * @param string $string
    * @param boolean $stripTags
    * @return string
    */
    protected function _clearStringData($string, $stripTags = true)
    {
        $encoding = mb_detect_encoding($string);
        $string = trim($string);
        $string = str_replace(array('&nbsp;','&ndash;'), array('',''), $string);
        $string = html_entity_decode($string);
        if ($stripTags) {
            $string = strip_tags($string);
        }
        $string = iconv('cp1251', $encoding, iconv($encoding, 'cp1251//ignore', $string));
        $string = trim($string);
        return $string;
    }

    /**
    * @param string $phraze
    * @return string
    */
    protected function _findLinkForPhraze($phraze)
    {
        $link = null;
        $tags = $this->_getParser()->findSurroundingTags($phraze);
        $links = $this->_getMiner()->getLinks($tags);
        reset($links);
        $link = current($links);
        return html_entity_decode($link);
    }

    /**
    * @return Lib_Config_Interface
    */
    final protected function _getConfig()
    {
        $config = ParserIKData_ServiceLocator::getInstance()->getConfigForFile($this->_getConfigFileName());
        return $config;
    }

    /**
     * @return Lib_Html_Parser
     */
    final protected function _getParser()
    {
        if ($this->_parser === null) {
            $this->_parser = new Lib_Html_Parser();
        }
        return $this->_parser;
    }
    /**
     * @return Lib_Html_DataMiner
     */
    final protected function _getMiner()
    {
        if ($this->_miner === null) {
            $this->_miner = new Lib_Html_DataMiner();
        }
        return $this->_miner;
    }

    /**
     * @param string $url
     * @param boolean $useCache
     * @return Ambigous <string, boolean>
     */
    final protected function _getPageContent($url, $useCache)
    {
        return $this->_getLoader()
            ->setSource($this->_getSite() . $url)
            ->setUseCache($useCache)
            ->setCache(ParserIKData_ServiceLocator::getInstance()->getWebCache())
            ->load();
    }

    /**
     * @return string|null
     */
    protected function _getSiteEncoding()
    {
        return null;
    }


    /**
     * @param string $link
     * @return string
     */
    protected function _excludeSiteFromLink($link)
    {
        return str_replace($this->_getSite(), '', $link);
    }

    /**
     * @return Lib_Html_Loader
     */
    final protected function _getLoader()
    {
        if ($this->_loader === null) {
            $this->_loader = new Lib_Html_Loader('', true);
            if ($this->_getSiteEncoding()) {
                $this->_loader->setInputEncoding($this->_getSiteEncoding());
            }
        }
        return $this->_loader;
    }
}