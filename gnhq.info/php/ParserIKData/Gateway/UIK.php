<?php
class ParserIKData_Gateway_UIK extends ParserIKData_Gateway_Abstract
{
    private $_table = 'uik';

    public function getForOkrug($okrug)
    {
        $query = 'SELECT * FROM ' . $this->_table . ' WHERE ' . $this->_table . '.FullName IN (' . $this->getCondOkrug($okrug->getAbbr()) . ')';
        $result = $this->_getDriver()->query($query);
        $uiks = array();
        while ( ($data = mysql_fetch_array($result, MYSQL_NUM)) !== false) {
            $uik = ParserIKData_Model_UIK::fromArray($data);
            $uiks[$uik->getUniqueId()] = $uik;
        }
        return $uiks;
    }

    public function getCondOkrug($okrugAbbr)
    {
        $tikOkrugCond = $this->_getTikGateway()->getCondOkrug($okrugAbbr);
        return 'SELECT FullName FROM ' . $this->_table . ' WHERE TikUniqueId IN (' . $tikOkrugCond .')';
    }

    private function _getTikGateway()
    {
        return new ParserIKData_Gateway_TIK();
    }
}