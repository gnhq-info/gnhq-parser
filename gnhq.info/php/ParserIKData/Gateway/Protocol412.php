<?php
class ParserIKData_Gateway_Protocol412 extends ParserIKData_Gateway_Abstract
{
    private $_table = 'result_412';

    /**
     * @param string $okrugAbbr
     * @return ParserIKData_Model_Protocol412|NULL
     */
    public function getOfficialResultForOkrug($okrugAbbr)
    {
        $query = $this->_buildSumQuery($this->_getCondOfficial() . ' AND ('.$this->_getCondOkrug($okrugAbbr).')');
        return $this->_fetchSumProtocol($query);
    }

    /**
     * @param int $uikNum
     * @return ParserIKData_Model_Protocol412|NULL
     */
    public function getOfficialResultForUik($uikNum)
    {
        $query = $this->_buildSumQuery($this->_getCondOfficial() . ' AND ' . $this->_getCondUik($uikNum));
        return $this->_fetchSumProtocol($query);
    }

    /**
     * @return ParserIKData_Model_Protocol412|NULL
     */
    public function getOfficialResultForMoscow()
    {
        $query = $this->_buildSumQuery($this->_getCondOfficial());
        return $this->_fetchSumProtocol($query);
    }


    /**
     * @param string $query
     * @return ParserIKData_Model_Protocol412|NULL
     */
    private function _fetchSumProtocol($query)
    {
        $result = $this->_getDriver()->query($query);
        if (!$result) {
            return null;
        }
        while ( ($data = mysql_fetch_array($result, MYSQL_NUM)) !== false) {
            $protocol = ParserIKData_Model_Protocol412::fromArray($data);
            return $protocol;
        }
        return null;
    }

    /**
     * @param string $condString
     * @return string
     */
    private function _buildSumQuery($condString)
    {
        return 'SELECT ' . $this->_getSumSelect() . ' FROM ' . $this->_table . ' WHERE ' . $condString;
    }

    /**
     * @return string
     */
    private function _getCondOfficial()
    {
        return 'ResultType = "OF"';
    }

    /**
     * @param int $uikNum
     * @return string
     */
    private function _getCondUik($uikNum)
    {
        return ' IkType = "UIK" AND IkFullName = ' . intval($uikNum) ;
    }

    /**
     * @param string $okrugAbbr
     */
    private function _getCondOkrug($okrugAbbr)
    {
        $uikCond = $this->_getUikGateway()->getCondOkrug($okrugAbbr);
        return ' IkType = "UIK" AND IkFullName IN ('.$uikCond.')';
    }

    /**
     * @return string
     */
    private function _getSumSelect()
    {
        $statement = ' "Mixed" AS IkFullName, "Mixed" AS IkType, "Mixed" AS ResultType, SUM(ClaimCount) AS ClaimCount ';
        for ($i = 1; $i < 26; $i++) {
            $statement .= ', SUM(Line' . $i . ') AS Line' . $i;
        }
        return $statement;
    }

    /**
     * @return ParserIKData_Gateway_UIK
     */
    private function _getUikGateway()
    {
        return new ParserIKData_Gateway_UIK();
    }
}