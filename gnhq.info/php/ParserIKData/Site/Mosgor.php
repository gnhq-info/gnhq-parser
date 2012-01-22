<?php
/**
 * Парсер сайта мосгоризбиркома
 * @author admin
 */
class ParserIKData_Site_Mosgor
{
    private $_debugCnt = 0;
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
     * создание всех объектов по первой иерархии (страница Информация территориальных избирательных комиссий)
     * @return ParserIKData_Site_Mosgor
     */
    public function initModelsByHierarchy()
    {
        $result = $this->_getPageContent($this->_getCValue('hierarchy.page'), true);

        // find links
        $okrugString = $this->_getCValue('hierarchy.okrugIndicator');
        $this->_getParser()->setPageSource($result);
        $okrugTags = $this->_getParser()->findSurroundingTags($okrugString);
        $okrugLinks = $this->_getMiner()->getLinks($okrugTags);

        // tiks
        $tikString = $this->_getCValue('hierarchy.tikIndicator');
        foreach($okrugLinks as $okrugName => $okrugLink) {
            //print_r($okrugName .': ' . $okrugLink . PHP_EOL);
            $okrug = ParserIKData_Model_Okrug::createFromPageInfo($okrugName, $okrugLink, array());
            $tikResult = $this->_getPageContent($okrugLink, true);
            $this->_getParser()->setPageSource($tikResult);
            $tikTags = $this->_getParser()->findSurroundingTags($tikString);
            $tikLinks = $this->_getMiner()->getLinks($tikTags);
            // print_r($tikLinks);
            foreach ($tikLinks as $tikName => $tikLink) {
                $tikRealName = trim(str_replace($tikString, '', $tikName));
                $tikRealName = $this->_clearStringData($tikRealName, true);
                $tik = ParserIKData_Model_TIK::createFromPageInfo($tikRealName, $tikLink, array());
                $okrug->addTik($tik);
            }
        }
        return $this;
    }

    /**
     * @return ParserIKData_Site_Mosgor
     */
    public function loadTikDataForOkrugs()
    {
        $okrugString = $this->_getCValue('tikdata.okrugIndicator');
        $result = $this->_getPageContent($this->_getCValue('tikdata.page'), true);
        $this->_getParser()->setPageSource($result);
        $okrugTags = $this->_getParser()->findSurroundingTags($okrugString);
        $okrugTikLinks = $this->_getMiner()->getLinks($okrugTags);

        foreach ($okrugTikLinks as $name => $link) {
            $okrug = ParserIKData_Model_Okrug::findByModifiedName($name, array('- В'));
            if ($okrug) {
                $okrug->setTikDataLink($link);
            }
        }
        return $this;
    }

    /**
     * @return ParserIKData_Site_Mosgor
     */
    public function loadTikAddressAndSostavLinks()
    {
        foreach (ParserIKData_Model_Okrug::getAllObjects() as $okrug) {
            /* loading tik links */
            $result = $this->_getPageContent($okrug->getTikDataLink(), true);
            $this->_getParser()->setPageSource($result);
            $tikTags = $this->_getParser()->findSurroundingTags($this->_getCValue('tikdata.tikIndicator'));
            $tikTagLinks = $this->_getMiner()->getLinks($tikTags);

            foreach ($tikTagLinks as $tikName => $tikLink) {
                /* @var $tik ParserIKData_Model_TIK */
                $tik = ParserIKData_Model_TIK::findByModifiedName($tikName);
                if ($tik instanceof ParserIKData_Model_TIK) {
                    $tik->setSelfInfoLink($tikLink);
                }
            }
        }

        foreach (ParserIKData_Model_TIK::getAllOBjects() as $tik) {
            if ($tik->getSelfInfoLink() != '') {
                $result = $this->_getPageContent($tik->getSelfInfoLink(), true);
                $this->_getParser()->setPageSource($result);

                $tik->setSostavLink($this->_findLinkForPhraze($this->_getCValue('tikaddress.sostavIndicator')));
                $tik->setAddressLink($this->_findLinkForPhraze($this->_getCValue('tikaddress.addressIndicator')));
            }
        }
        return $this;
    }


    /**
     * @return ParserIKData_Site_Mosgor
     */
    public function loadTikData()
    {
        foreach (ParserIKData_Model_TIK::getAllOBjects() as $tik) {
            $this->_loadTikAddress($tik);
            $this->_loadTikSostav($tik);
        }
        return $this;
    }

    /**
     * @param ParserIKData_Model_TIK $tik
     * @return ParserIKData_Site_Mosgor
     */
    public function createTikUiks(ParserIKData_Model_TIK $tik)
    {
        $page = $this->_getPageContent(html_entity_decode($tik->getLink()), true);

        $printVersionPage = $this->_getPageContent($this->_getPrintVersionLink($page), true);

        $string = $this->_getCValue('uik.tableIndicator');
        $table = $this->_getParser()->setPageSource($printVersionPage)->findMinContainingTag($string, 'table');
        $this->_createUiksByTable($table, $tik);

        // patch for Щукино - two tables on page
        if ($tik->getUniqueId() == ParserIKData_Model_TIK::findByModifiedName('Щукино')->getUniqueId()) {
            $table = $this->_getParser()->findMinContainingTag('2992', 'table');
            $this->_createUiksByTable($table, $tik);
        }
        print_r($tik->getFullName(). ' processed' . PHP_EOL);
        return $this;
    }

    /**
     * @param html $table
     * @param ParserIKData_Model_TIK $tik
     */
    private function _createUiksByTable($table, $tik)
    {
        if (!$table) {
            return;
        }
        $rows = $this->_getMiner()->extractRows($table, 100);
        foreach ($rows as $row) {
            $cells = $this->_getMiner()->extractCells($row, 100);
            $uik = $this->_createUikFromTableCells($cells, $tik);
        }
    }

    /**
     * @param string $cells[]
     * @param ParserIKData_Model_TIK @tik
     * @return ParserIKData_Model_UIK|null
     */
    private function _createUikFromTableCells($cells, $tik)
    {
        foreach ($cells as $i => $cell) {
            $cells[$i] = $this->_clearStringData($cell);
            if ($i == 0 && !is_numeric($cells[$i])) {
                return null;
            }
        }
        $existingUik = ParserIKData_Model_UIK::getFromPool($cells[0]);
        if (!$existingUik) {
            $uik = ParserIKData_Model_UIK::createFromPageInfo($cells[0], '', array());
            /* @var $uik ParserIKData_Model_UIK */
            $uik->setBorderDescription($cells[1])->setPlace($cells[2])->setVotingPlace($cells[3])->_friendSetTik($tik);
            $tik->addUik($uik);
            return $uik;
        } else {
            print_r($tik->getFullName() . ': ' .implode('|' ,$cells) . PHP_EOL . str_repeat('!', 20). PHP_EOL);
            return null;
        }
    }

    /**
     * @param string $page
     * @return string
     */
    private function _getPrintVersionLink($page)
    {
        $this->_getParser()->setPageSource($page);
        $data = $this->_getParser()->findSurroundingTags($this->_getCValue("printVersionIndicator"));
        $links = $this->_getMiner()->getLinks($data);
        reset($links);
        $printVersionLink = current($links);
        return $printVersionLink;
    }

    /**
     * @param ParserIKData_Model_TIK $tik
     * @return ParserIKData_Model_TIK
     */
    private function _loadTikAddress($tik)
    {
        $result = $this->_getPageContent($tik->getAddressLink(), true);

        $address = $this->_clearStringData($this->_getParser()
            ->stringInBetween($result, $this->_getCValue('tikaddress.addressStart'), $this->_getCValue('tikaddress.addressFinish'), false));
        $pos = strpos($address, $this->_getCValue('tikaddress.addressPhoneStart'));
        if ($pos > 0) {
            $address = substr($address, 0, $pos);
        }

        $phone   = $this->_clearStringData($this->_getParser()
            ->stringInBetween($result, $this->_getCValue('tikaddress.phoneStart'), $this->_getCValue('tikaddress.phoneFinish'), false));
        $phone = $this->_excludeCArray('tikaddress.phoneExcludes', $phone);

        return $tik->setAddress($address)->setPhone($phone);
    }

    /**
     * @param ParserIKData_Model_TIK $tik
     * @return ParserIKData_Model_TIK
     */
    private function _loadTikSostav($tik)
    {
        $result = $this->_getPageContent($tik->getSostavLink(), true);

        $sostavHtml = $this->_getParser()
            ->stringInBetween($result, $this->_getCValue('tiksostav.start'), $this->_getCValue('tiksostav.finish'), false);
        $this->_parseAndLoadSostavData($sostavHtml, $tik);
        return $tik;
    }


    /**
     * @param string $phraze
     * @return string
     */
    private function _findLinkForPhraze($phraze)
    {
        $link = null;
        $tags = $this->_getParser()->findSurroundingTags($phraze);
        $links = $this->_getMiner()->getLinks($tags);
        reset($links);
        $link = current($links);
        return html_entity_decode($link);
    }

    /**
     * @param string $sostav
     * @param ParserIKData_Model_TIK $tik
     * @return ParserIKData_Model_TIK
     */
    private function _parseAndLoadSostavData($sostav, $tik)
    {
        $sostav = $this->_clearStringData($sostav, false);
        $sostavParts = explode($this->_getCValue('tiksostav.separator'), $sostav);
        $chief       = null;
        $deputy      = null;
        $secretary   = null;
        $members     = array();
        foreach ($sostavParts as $k => $part) {

            $part = $this->_clearStringData($part, true);

            if (!trim($part)) {
                continue;
            }
            if (mb_stristr($part, $this->_getCValue('tiksostav.memberStringIndicator'), null, mb_detect_encoding($part)) !== false) {
                continue;
            }
            if ($this->_getChief($part)) {
                $chief = $this->_getChief($part);
                $chief = $this->_excludeCArray('tiksostav.chiefExcludes', $chief);
                $chief = trim($chief);
                continue;
            }
            if ($this->_getDeputy($part)) {
                $deputy = $this->_getDeputy($part);
                $deputy = $this->_excludeCArray('tiksostav.deputyExcludes', $deputy);
                $deputy = trim($deputy);
                continue;
            }
            if ($this->_getSecretary($part)) {
                $secretary = $this->_getSecretary($part);
                $secretary = $this->_excludeCArray('tiksostav.secretaryExcludes', $secretary);
                $secretary = trim($secretary);
                continue;
            }
            if (trim($part)) {
                $members[] = $part;
            }
        }

        $tik->setChief($chief)->setDeputy($deputy)->setSecretary($secretary)->setMembers($members);
        //$tik->DEBUG_PRINT();
        return $tik;
    }


    private function _getChief($string)
    {
        if (mb_stripos($string, $this->_getCValue('tiksostav.chiefIndicator')) !== false) {
            return $string;
        } else {
            return false;
        }
    }

    private function _getDeputy($string)
    {
        if (mb_stripos($string, $this->_getCValue('tiksostav.deputyIndicator')) !== false) {
            return $string;
        } else {
            return false;
        }
    }

    private function _getSecretary($string)
    {
        if (mb_stripos($string, $this->_getCValue('tiksostav.secretaryIndicator')) !== false) {
            return $string;
        } else {
            return false;
        }
    }

    /**
     * @param string $string
     * @param boolean $stripTags
     * @return string
     */
    private function _clearStringData($string, $stripTags = true)
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
     * @return Ambigous <string, NULL>
     */
    private function _getSite()
    {
        return $this->_getCValue('site');
    }

    /**
     * @param string $key
     * @return string|null
     */
    private function _getCValue($key)
    {
        return $this->_getConfig()->getValue($key);
    }

    /**
     * @param string $key
     * @return array|null
     */
    private function _getCArray($key)
    {
        return $this->_getConfig()->getArrayValue($key);
    }

    /**
     * @param string $key
     * @param string $string
     * @return string
     */
    private function _excludeCArray($key, $string)
    {
        $array = $this->_getCArray($key);
        if (!$array) {
            return $string;
        }
        $replace = array_fill(0, count($array), '');
        return str_replace($array, $replace, $string);
    }

    /**
     * @return Lib_Config_Interface
     */
    private function _getConfig()
    {
        $config = ParserIKData_ServiceLocator::getInstance()->getConfigForFile('mosgor.ini');
        return $config;
    }

    /**
    * @return Lib_Html_Parser
    */
    private function _getParser()
    {
        if ($this->_parser === null) {
            $this->_parser = new Lib_Html_Parser();
        }
        return $this->_parser;
    }
    /**
     * @return Lib_Html_DataMiner
     */
    private function _getMiner()
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
    private function _getPageContent($url, $useCache)
    {
        return $this->_getLoader()->setSource($this->_getSite() . $url)->setUseCache($useCache)->load();
    }

    /**
     * @return Lib_Html_Loader
     */
    private function _getLoader()
    {
        if ($this->_loader === null) {
            $this->_loader = new Lib_Html_Loader('', true);
            $this->_loader->setCacheDir(rtrim(APPLICATION_DIR_ROOT, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'Cache');
        }
        return $this->_loader;
    }
}