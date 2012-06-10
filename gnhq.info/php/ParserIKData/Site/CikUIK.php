<?php
class ParserIKData_Site_CikUIK extends ParserIKData_Site_Cik
{
    protected function _getConfigFileName()
    {
        return '';
    }

    public function getProtocolData($src)
    {
        $this->_getLoader()->setSource($src);
        $html = $this->_getLoader()->load();
        $this->_getParser()->setPageSource($html);
        $resultTable = $this->_getParser()->findMinContainingTag('число избирателей', 'table');
        $trs = $this->_getMiner()->extractRows($resultTable, 100);
        $data = array();
        foreach ($trs as $tr) {
            $tds = $this->_getMiner()->extractCells($tr, 3);
            if (count($tds) == 3) {
                $ind = strip_tags($tds[0]);
                $b = $this->_getMiner()->extractBold($tds[2], 1);
                $val = strip_tags($b[0]);
                $data[$ind] = $val;
            }
        }
        return $data;
    }
}