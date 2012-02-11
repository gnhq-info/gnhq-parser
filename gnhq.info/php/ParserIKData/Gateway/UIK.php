<?php
class ParserIKData_Gateway_UIK extends ParserIKData_Gateway_Abstract
{
    private $_table = 'uik';

    /**
     * @param string $okrugAbbr
     * @param string|null $watchType
     * @return ParserIKData_Model_UIK[]
     */
    public function getForOkrug($okrugAbbr, $watchType = null)
    {
        $conds = array();
        $conds[] =  'FullName IN (' . $this->_getCondOkrug($okrugAbbr) . ')';
        if ($watchType) {
            $conds[] = 'FullName IN ( '. $this->_getCondWatchType($watchType). ')';
        }
        $cond = '( ' . implode(' ) AND (', $conds) . ' )';
        $result = $this->_getDriver()->select('*', $this->_table, $cond, null, null);
        $uiks = array();
        while ( ($data = $this->_fetchResultToArray($result)) !== false) {
            $uik = ParserIKData_Model_UIK::fromArray($data);
            $uiks[$uik->getUniqueId()] = $uik;
        }
        return $uiks;
    }

    public function getCondOkrug($okrugAbbr)
    {
        return $this->_getCondOkrug($okrugAbbr);
    }

    private function _getCondOkrug($okrugAbbr)
    {
        $tikOkrugCond = $this->_getTikGateway()->getCondOkrug($okrugAbbr);
        return 'SELECT FullName FROM ' . $this->_table . ' WHERE TikUniqueId IN (' . $tikOkrugCond .')';
    }

    private function _getCondWatchType($watchType)
    {
        return $this->_getWatchGateway()->getCondIn($watchType);
    }
}