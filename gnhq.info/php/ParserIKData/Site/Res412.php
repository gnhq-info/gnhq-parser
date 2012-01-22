<?php
class ParserIKData_Site_Res412 extends ParserIKData_Site_Abstract
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Site_Abstract::_getConfigFileName()
     */
    protected function _getConfigFileName()
    {
        return 'res412.ini';
    }

    protected function _getSiteEncoding()
    {
        return 'WINDOWS-1251';
    }

    /**
     * array Okrug => Link
     * @return Ambigous <mixed, multitype:, multitype:Ambigous <Ambigous <string, false, boolean>> >
     */
    public function getOkrugLinks()
    {
        return $this->_getLowerLinks($this->_getCValue('rootPage'));
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
        $page = $this->_getPageContent($page, true);
        $select = $this->_lowerIKsSelect($page);
        $options = $this->_getMiner()->extractOptions($select, 50);
        $optionData = $this->_getMiner()->getOptions($options);
        foreach ($optionData as $okrug => $link) {
            if ($link != '') {
                $optionData[$okrug] = $this->_excludeSiteFromLink(html_entity_decode($link));
            } else {
                unset ($optionData[$okrug]);
            }
        }
        return $optionData;
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