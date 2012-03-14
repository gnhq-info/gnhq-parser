<?php
abstract class ParserIKData_Site_Cik extends ParserIKData_Site_Abstract
{

    protected function _getSiteEncoding()
    {
        return 'WINDOWS-1251';
    }

    /**
    * @param string $page
    * @return Ambigous <mixed, multitype:, multitype:Ambigous <Ambigous <string, false, boolean>> >
    */
    protected function _getLowerLinks($page)
    {
        $page = $this->_getPageContent($page, $this->_getCValue('useCache'));
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
    * @param string $page
    * @return Ambigous <string, false, boolean>
    */
    protected function _lowerIKsSelect($page)
    {
        $form = $this->_getParser()->setPageSource($page)->findMinContainingTag($this->_getCValue('lowerIKIndicator'), 'form');
        $select = $this->_getParser()->setPageSource($form)->findMinContainingTag($this->_getCValue('lowerIKSelectIndicator'), 'select');
        return $select;
    }
}