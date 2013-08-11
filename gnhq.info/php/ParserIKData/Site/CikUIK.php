<?php
class ParserIKData_Site_CikUIK extends ParserIKData_Site_Cik
{
    /**
    * @param ParserIKData_Model_UIKRussia $uik
    * @return ParserIKData_Model_Protocol
    */
    public function getOficialProtocol($uik)
    {
        $data = $this->_getProtocolData($uik->getLink());
        $proto = $this->_createBlankProtocol();
        $proto
            ->setResultType(ParserIKData_Model_Protocol::TYPE_OF)
            ->setProjectId($uik->getFullName())
            ->setUpdateTime(date('y-m-d h:i:s'))
            ->setIkFullName($uik->getFullName())
            ->setData($data);
        return $proto;
    }


    protected function _getConfigFileName()
    {
        return '';
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
                $b = $this->_getMiner()->extractBold($tds[2], 1);
                $val = strip_tags($b[0]);
                $data[$ind] = $val;
            }
        }
        return $data;
    }

    protected function _createBlankProtocol()
    {
        throw new Exception('must be implemented');
    }
}