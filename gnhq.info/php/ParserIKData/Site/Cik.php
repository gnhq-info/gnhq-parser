<?php
abstract class ParserIKData_Site_Cik extends ParserIKData_Site_Abstract
{

    protected function _getSiteEncoding()
    {
        return 'WINDOWS-1251';
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
        $cells = $this->_transponeTable($cells);
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
    * @param string $page
    * @return Ambigous <mixed, multitype:, multitype:Ambigous <Ambigous <string, false, boolean>> >
    */
    protected function _getLowerLinks($page)
    {
        $page = $this->_getPageContent($page, $this->_getCValue('useCache'));
        $select = $this->_lowerIKsSelect($page);
        $options = $this->_getMiner()->extractOptions($select, 200);
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

    /**
     * @param string[][] $cells
     * @return string[][]
     */
    protected function _transponeTable($cells)
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
    protected function _prepareHeaderCellValue($cell)
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
    protected function _prepareCellValue($cell, $skipPercent)
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