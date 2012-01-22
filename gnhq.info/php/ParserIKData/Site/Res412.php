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
     * @param string $link
     * @return Ambigous <string, false, boolean>
     */
    public function getResultTable($link)
    {
        $this->_getParser()->setPageSource($this->_getPageContent($link, true));
        $totalTable = $this->_getParser()->findMinContainingTag($this->_getCValue('totalTableIndicator'), 'table');
        $resultTable = $this->_getParser()->findMinContainingTag($this->_getCValue('resultTableIndicator'), 'table');
        return $resultTable;
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
                    $rowCells[$j] = $this->_prepareCellValue($cell);
                }
            }
            $cells[$k] = $rowCells;
        }
        $cells = $this->_transoneTable($cells);
        $results = array();
        foreach ($cells as $k => $row) {
            $name = array_shift($row);
            $results[$name] = $row;
        }
        return $results;
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
                if ($cell) {
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
     * @return Ambigous <multitype:Ambigous, multitype:Ambigous <string, false, boolean> >
     */
    private function _prepareCellValue($cell)
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
        return array('numeric' => $numericValue, 'percent' => $percentValue);
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