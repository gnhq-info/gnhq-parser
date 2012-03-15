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
                array(
                    $this->_getCValue('tikLinkPreffix'),
                    $this->_getCValue('tikLinkPreffix2'),
                    $this->_getCValue('tikLinkPreffix3'),
                    $this->_getCValue('tikLinkPostfix'),
                    $this->_getCValue('tikLinkPostfix2'),
                ),
                array('', '', '', '', ''),
                $text)
            );
            if ($text == $this->_getCValue('presidentLinkIndicator')) {
                continue;
            }
            $link = html_entity_decode($this->_getMiner()->getHref($tikA));
            $links[$text] = $link;
            // break; // @TEMP - for testing
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
                $candidats[$num - $candidatsMin + 1] = str_replace(
                    array('    ', '   ', '  '),
                    array(' ', ' ', ' '),
                    trim(strip_tags($cells[1]))
                );
            }
        }
        return $candidats;
    }


    /**
    * @param string $link
    * @return Ambigous <string, false, boolean>
    */
    public function getOkrugResults($oLink)
    {
        $this->_getParser()->setPageSource($this->_getPageContent($oLink, $this->_getCValue('useCache')));
        $resultTable = $this->_getParser()->findMinContainingTag($this->_getCValue('resultTableIndicator'), 'table');
        $results = $this->getResultsFromTable($resultTable);
        return $results;
    }
}