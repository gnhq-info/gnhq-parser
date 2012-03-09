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

    protected function _getSiteEncoding()
    {
        return 'WINDOWS-1251';
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
     * @return Ambigous <string, false, boolean>
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
     * @param html $table
     */
    public function getResultsFromTable($table)
    {
        $cells = array();
        $rows = $this->_getMiner()->extractRows($table, 50);
        foreach ($rows as $k => $row) {
            $rowCells = $this->_getMiner()->extractCells($row, 100);
            foreach ($rowCells as $j => $cell) {
                if ($k == 0) {
                    $rowCells[$j] = $this->_prepareHeaderCellValue($cell);
                } else {
                    $rowCells[$j] = $this->_prepareCellValue($cell, true);
                }
            }
            $cells[$k] = $rowCells;
        }
        $cells = $this->_transoneTable($cells);
        $results = array();
        foreach ($cells as $k => $row) {
            $name = array_shift($row);
            $i = 1;
            foreach ($row as $j => $v) {
                $results[$name][$i] = $v;
                $i++;
            }
        }
        return $results;
    }


    /**
     * @param string $uikName
     * @param array $data
     * @return ParserIKData_Model_Protocol412
     */
    public function createResult($uikName, $data)
    {
        $uikNumber = str_replace('УИК №', '', $uikName);
        $result = ParserIKData_Model_Protocol412::createFromPageInfo($uikNumber, '', array());
        /* @var $result ParserIKData_Model_Protocol412 */
        $result->setTypeOf();
        $result->setData($data);
        $result->setIkTypeUIK();
        return $result;
    }


    /**
     * @param string[][] $cells
     * @return string[][]
     */
    private function _transoneTable($cells)
    {
        $results = array();
        foreach ($cells as $k => $row) {
            foreach ($row as $j => $cell) {
                if ($cell !== '') {
                    $results[$j][$k] = $cell;
                }
            }
        }
        return $results;
    }

    /**
     * @param html $cell
     * @return Ambigous <multitype:Ambigous, multitype:Ambigous <string, false, boolean> >
     */
    private function _prepareHeaderCellValue($cell)
    {
        $nobrs = $this->_getMiner()->extractNobr($cell, 5);
        $nobr = $nobrs[0];
        $nobr = strip_tags($nobr);
        return $nobr;
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

    /**
     * @param string $page
     * @return Ambigous <mixed, multitype:, multitype:Ambigous <Ambigous <string, false, boolean>> >
     */
    private function _getLowerLinks($page)
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