<?php
class ParserIKData_Site_Gn412 extends ParserIKData_Site_Abstract
{
    /**
     * @return ParserIKData_Model_Report412[]
     */
    public function getReportList()
    {
        $this->_getParser()->setPageSource($this->_getPageContent($this->_getCValue('rootPage'), $this->_getCValue('useCache')));
        $table = $this->_getParser()->findMinContainingTag($this->_getCValue('reportTableIndicator'), 'table');
        $rows = $this->_getMiner()->extractRows($table, 5000);
        $reports = array();
        $hdrProcessed = false;
        foreach ($rows as $row) {
            if (!$hdrProcessed) {
                $hdrProcessed = true;
                continue;
            }
            $res = $this->_rowToReport($row);
            if ($res) {
                $reports[] = $res;
            }
        }
        return $reports;
    }

    /**
     * @param ParserIKData_Model_Report412 $report
     * @return ParserIKData_Model_Report412
     */
    public function loadContent($report)
    {
        if (!$report->getLink()) {
            return $report;
        } else {
            $data = $this->_getPageContent($report->getLink(), $this->_getCValue('useCache'));
            $content = $this->_getParser()->setPageSource($data)->findMinContainingTag('id="content"', 'div');
            $enc = $this->_getSiteEncoding();
            $tableStart = mb_strpos($content, '<table', 0, $enc);
            $tableEnd = mb_strpos($content, '</table>', 0, $enc) + mb_strlen('</table>', $enc);
            $preffix = mb_substr($content, 0, $tableStart, $enc);
            $postfix = mb_substr($content, $tableEnd, -1, $enc);
            $this->_explodePreffix($report, $preffix);
        }
    }


    /**
     * @param ParserIKData_Model_Report412 $report
     * @param string $preffix
     * @return ParserIKData_Model_Report412
     */
    private function _explodePreffix($report, $preffix)
    {
        $author = $this->_clearString($this->_getParser()->stringInBetween($preffix, 'last">', '[', false));
        $authorParts = explode(',', $author);
        $author = $authorParts[count($authorParts)-1];
        $report->setAuthor($this->_clearString($author));
        $shortDescr = $this->_getParser()->stringInBetween($preffix, '[', ']', false);
        $shortDescr = $this->_clearString($shortDescr);
        $report->setShort($shortDescr);
        return $report;
    }


    /**
     * @var string $string
     * @return string     *
     */
    private function _clearString($string)
    {
        $enc = mb_detect_encoding($string);
        $string = strip_tags($string);
        $string = iconv($enc, 'cp1251//ignore', $string);
        $string = preg_replace('/[\s]*$|^[\s]*/i', '', $string);
        $string = iconv('cp1251', $enc, $string);
        return $string;
    }

    /**
     * @param array $row
     * @return ParserIKData_Model_Report412
     */
    private function _rowToReport($row)
    {
        $cellData = array();
        $cells = $this->_getMiner()->extractCells($row, 5);
        $cellData['FullName'] = intval(trim(strip_tags($cells[1])));
        $cellData['Ocenka'] = str_replace(" ", "", $this->_clearStringData($cells[2], true));
        $a = $this->_getMiner()->extractA($cells[3], 5);
        if (!empty($a)) {
            $links = $this->_getMiner()->getLinks($a);
            reset($links);
            $cellData['Link'] = ltrim(current($links), '/');
        } else {
            $cellData['Link'] = '';
        }
        if ($cellData['FullName']) {
            $report = ParserIKData_Model_Report412::createFromPageInfo($cellData['FullName'], $cellData['Link'], array());
            $report->setOcenka(trim($cellData['Ocenka']));
            return $report;
        } else {
            return null;
        }
    }

    /**
    * (non-PHPdoc)
    * @see ParserIKData_Site_Abstract::_getConfigFileName()
    */
    protected function _getConfigFileName()
    {
        return 'gn412.ini';
    }

    protected function _getSiteEncoding()
    {
        return 'UTF-8';
    }
}