<?php
/**
 * @author admin
 */
class ParserIKData_Site_CikMarch extends ParserIKData_Site_Abstract
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Site_Abstract::_getConfigFileName()
     */
    protected function _getConfigFileName()
    {
        return 'init403.ini';
    }

    protected function _getSiteEncoding()
    {
        return 'WINDOWS-1251';
    }

    /**
     * array region => Link
     * @return string[]
     */
    public function getRegionLinks()
    {
        $data = $this->_getLowerLinks($this->_getCValue('rootPage'));
        $nData = array();
        foreach ($data as $regionName => $link) {
            print (PHP_EOL . str_repeat('-', 20) . PHP_EOL);
            print ($regionName . PHP_EOL);
            $nData[$regionName] = '';

            $tikLinks = $this->_getLowerLinks(html_entity_decode($link));
            reset($tikLinks);
            $fLink = current($tikLinks);
            if (!$fLink) {
                print ('no flink' . PHP_EOL);
                continue;
            }
            $content = $this->_getPageContent($fLink, $this->_getCValue('useCache'));

            $data = $this->_getParser()->setPageSource($content)->findMinContainingTag($this->_getCValue('regionIndicator'), 'a');

            $nLink = html_entity_decode($this->_getMiner()->getHref($data));
            if (!$nLink) {
                print ('no nlink' . PHP_EOL);
                continue;
            }
            $content = $this->_getPageContent($nLink, $this->_getCValue('useCache'));

            $siteLink = $this->_getParser()->stringInBetween($content, '<base href="', '">', false);

            $startPage = $this->_getParser()->setPageSource($content)->findMinContainingTag(trim($regionName), 'a');
            $pageLink = $this->_getMiner()->getHref($startPage);

            $totalLink = html_entity_decode(rtrim($siteLink, '/'). '/' . $pageLink);
            $nData[$regionName] = $totalLink;
        }
        return $nData;
    }

    /**
     * @param string $link
     * @return Ambigous <Ambigous, mixed, multitype:, multitype:Ambigous <Ambigous <string, false, boolean>> >
     */
    public function getTIKLinks($link)
    {
        return $this->_getLowerLinks($link);
    }


    /**
     * @param string $page
     * @return Ambigous <mixed, multitype:, multitype:Ambigous <Ambigous <string, false, boolean>> >
     */
    private function _getLowerLinks($page)
    {
        $page = $this->_getPageContent($page, $this->_getCValue('useCache'));
        $select = $this->_lowerIKsSelect($page);
        $options = $this->_getMiner()->extractOptions($select, 1000);
        $optionData = $this->_getMiner()->getOptions($options);
        $rData = array();
        foreach ($optionData as $okrug => $link) {
            if ($link != '') {
                $rData[trim($okrug)] = $this->_excludeSiteFromLink(html_entity_decode($link));
            }
        }
        return $rData;
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $link
     * @return mixed
     */
    private function _excludeSiteFromLink($link)
    {
        return str_replace($this->_getSite(), '', $link);
    }

    /**
     * @param string $page
     * @return Ambigous <string, false, boolean>
     */
    private function _lowerIKsSelect($page)
    {
        $form = $this->_getParser()->setPageSource($page)->findMinContainingTag($this->_getCValue('lowerIKIndicator'), 'form');
        $select = $this->_getParser()->setPageSource($form)->findMinContainingTag($this->_getCValue('lowerIKSelectIndicator'), 'select');
        return $select;
    }
}