<?php
class ParserIKData_Site_Res403 extends ParserIKData_Site_Abstract
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Site_Abstract::_getConfigFileName()
     */
    protected function _getConfigFileName()
    {
        return 'res403.ini';
    }


    /**
     * @param ParserIKData_Model_UIKRussia $uikR
     * @return string
     */
    public function getResultLink($uikR)
    {
        $link = $uikR->getLink();
        if (!$link) {
            return '';
        }
        $cont = $this->_getPageContent($uikR->getLink(), true);
        $this->_getParser()->setPageSource($cont);
        $resLink = $this->_getParser()->findMinContainingTag($this->_getCValue('resLinkIndicator'), 'a');
        $resHref = $this->_getMiner()->getHref($resLink);
        $resHref = html_entity_decode($resHref);
        return $resHref;
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