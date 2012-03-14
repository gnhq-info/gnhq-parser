<?php
class ParserIKData_Site_MRes403 extends ParserIKData_Site_Cik
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Site_Abstract::_getConfigFileName()
     */
    protected function _getConfigFileName()
    {
        return 'mres403.ini';
    }


    public function getGeneralTikLinks()
    {
        $cont = $this->_getPageContent($this->_getCValue('startPage'), $this->_getCValue('useCache'));
        $this->_getParser()->setPageSource($cont);
        $table = $this->_getParser()->findMinContainingTag($this->_getCValue('tableIndicator'), 'table');
        $tikAs = $this->_getMiner()->extractA($table, 500);
        $links = array();
        foreach ($tikAs as $tikA) {
            $text = strip_tags($tikA);
            $text = trim(str_replace(
                array($this->_getCValue('tikLinkPreffix'), $this->_getCValue('tikLinkPreffix2'), $this->_getCValue('tikLinkPostfix')),
                array('', '', ''),
                $text)
            );
            if ($text == $this->_getCValue('presidentLinkIndicator')) {
                continue;
            }
            $link = html_entity_decode($this->_getMiner()->getHref($tikA));
            $links[$text] = $link;
            //break; // @TEMP - for testing
        }
        return $links;
    }

    /**
     * @param string $link
     * @return string
     */
    public function getResultLink($link)
    {
        $cont = $this->_getPageContent($link, $this->_getCValue('useCache'));
        $this->_getParser()->setPageSource($cont);
        $a = $this->_getParser()->findMinContainingTag($this->_getCValue('resLinkIndicator'), 'a');
        return html_entity_decode($this->_getMiner()->getHref($a));
    }


    /**
     * @param string $link
     * @return string[]
     */
    public function getOkrugLinks($link)
    {
        $olinks = $this->_getLowerLinks($link);
        return $olinks;
    }


    /**
     * @param string $oLink
     * @return string[]
     */
    public function getOkrugCandidats($oLink)
    {
        $candidats = array();
        $candidatsMin = 12;

        $cont = $this->_getPageContent($oLink, $this->_getCValue('useCache'));
        $this->_getParser()->setPageSource($cont);
        $table = $this->_getParser()->findMinContainingTag($this->_getCValue('rowHeadersIndicator'), 'table');
        $rows = $this->_getMiner()->extractRows($table, 75);
        foreach ($rows as $row) {
            $cells = $this->_getMiner()->extractCells($row, 5);
            $num = trim(strip_tags($cells[0]));
            if (is_numeric($num) && $num >= $candidatsMin) {
                $candidats[$num - $candidatsMin + 1] = trim(strip_tags($cells[1]));
            }
        }
        return $candidats;
    }


    /**
     * @param ParserIKData_Model_UIKRussia $uik
     * @param string $link
     * @return ParserIKData_Model_Protocol403
     */
    public function getProtocol($uik, $link)
    {
        $this->_getParser()->setPageSource($this->_getPageContent($link, $this->_getCValue('useCache')));
        $resultTable = $this->_getParser()->findMinContainingTag($this->_getCValue('tableIndicator'), 'table');
        $rows = $this->_getMiner()->extractRows($resultTable, 50);
        $data = array();
        foreach ($rows as $row) {
            $cells = $this->_getMiner()->extractCells($row, 50);
            $ind = strip_tags($cells[0]);
            if ($ind) {
                $val = $this->_prepareCellValue($cells[2], true);
                $data[$ind] = $val;
            }
        }
        $proto = ParserIKData_Model_Protocol403::create();
        $proto->setResultType(ParserIKData_Model_Protocol403::TYPE_OF);
        $proto->setData($data);
        $proto->setProjectId(0);
        $proto->setIkFullName($uik->getFullName());
        $proto->setClaimCount(0);
        $proto->setUpdateTime(date('Y-m-d H:i:s'));
        return $proto;
    }


    /**
     * @param html $cell
     * @param boolean $skipPercent
     * @return Ambigous <multitype:Ambigous, multitype:Ambigous <string, false, boolean> >
     */
    private function _prepareCellValue($cell, $skipPercent)
    {
        $bolds = $this->_getMiner()->extractBold($cell, 5);
        if (!is_array($bolds) || count($bolds) == 1) {
            $bold = $bolds[0];
        } else {
            $bold = '';
        }
        $remaining = str_replace($bold, '', $cell);
        $numericValue = trim(strip_tags($bold));
        $percentValue = trim(strip_tags($remaining));
        if ($skipPercent) {
            return $numericValue;
        } else {
            return array('numeric' => $numericValue, 'percent' => $percentValue);
        }
    }
}