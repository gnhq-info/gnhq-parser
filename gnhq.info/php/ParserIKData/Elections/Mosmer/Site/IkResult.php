<?php
class ParserIKData_Site_CikUIK_Mosmer extends ParserIKData_Site_CikUIK
{
    protected function _createBlankProtocol()
    {
        return ParserIKData_Model_Protocol_Mosmer::create();
    }

    protected function _getProtocolData($src)
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
                if (!is_numeric($ind)) {
                    continue;
                }
                if ($ind >= 8) {
                    if ($ind == 8) {
                        $data[$ind] = '0';
                    }
                    $ind = $ind + 1;
                }
                if ($ind >= 11) {
                    if ($ind == 11) {
                        $data[$ind] = '0';
                    }
                    $ind = $ind + 1;
                }
                $b = $this->_getMiner()->extractBold($tds[2], 1);
                $val = strip_tags($b[0]);
                $data[$ind] = $val;
            }
        }
        return $data;
    }
}