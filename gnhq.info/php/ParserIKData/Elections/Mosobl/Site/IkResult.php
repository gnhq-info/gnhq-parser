<?php
class ParserIKData_Site_CikUIK_Mosobl extends ParserIKData_Site_CikUIK
{
    protected function _createBlankProtocol()
    {
        return ParserIKData_Model_Protocol_Mosobl::create();
    }

    protected function _getProtocolData($src)
    {
        $this->_getLoader()->setCache(false)->setSource($src);
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
        return $this->_prepareData($data);
    }

    private function _prepareData($data)
    {
        $res = array();
        for ($i = 1; $i <=23; $i++ ) {
            if ($this->_mapIndex($i)) {
                $res[$this->_mapIndex($i)] = $data[$i];
            }
        }
        $res[8] = $res[11] = 0;
        ksort($res);
        return $res;
    }

    private function _mapIndex($ind)
    {
        if ($ind < 8) {
            return $ind;
        }
        if ($ind >= 8 && $ind <=9) {
            return ($ind + 1);
        }
        if ($ind >= 18) {
            return ($ind - 6);
        }
        return null;

    }
}