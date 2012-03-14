<?php
class ParserIKData_Site_Res412 extends ParserIKData_Site_Cik
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Site_Abstract::_getConfigFileName()
     */
    protected function _getConfigFileName()
    {
        return 'res412.ini';
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
        $this->_getParser()->setPageSource($this->_getPageContent($link, $this->_getCValue('useCache')));
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




}