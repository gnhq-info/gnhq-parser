<?php
class ParserIKData_Gateway_Protocol412 extends ParserIKData_Gateway_Abstract
{
    private $_table = 'result_412';


    /**
     * @param string $okrugAbbr
     * @param int $uikNum
     * @param string $resultType
     * @param boolean $onlyResultProtocols
     * @param boolean $onlyClean
     * @return ParserIKData_Model_Protocol412|NULL
     */
    public function getMixedResult($okrugAbbr = null, $uikNum = null, $resultType = null, $onlyResultProtocols = false, $onlyClean = false)
    {
        $condParts = array();
        if (!$resultType) {
            $condParts[] = $this->_getCondOfficial();
        } else {
            $condParts[] = $this->_getCondResultForType($resultType);
            if ($onlyResultProtocols) {
                $condParts[] = 'IkFullName IN (' . $this->_getCondHasProtocol($resultType) . ')';
            }
            if ($onlyClean) {
                $condParts[] = 'IkFullName IN (' . $this->_getWatchGateway()->getCondClear($resultType) . ')';
            }
        }
        if ($okrugAbbr) {
            $condParts[] = $this->_getCondOkrug($okrugAbbr);
        } elseif ($uikNum) {
            $condParts[] = $this->_getCondUik($uikNum);
        }
        $cond = '( ' . implode( ' ) AND ( ', $condParts) . ' )';
        $query = $this->_buildSumQuery($cond);
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
        while ( ($data = $this->_fetchResultToArray($result)) !== false) {
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
    * @param string $condString
    * @return string
    */
    private function _buildAllQuery($condString)
    {
        return 'SELECT * FROM ' . $this->_table . ' WHERE ' . $condString;
    }

    /**
     * @param string $resultType
     * @return string
     */
    private function _getCondVyborka($resultType)
    {
        return $this->_getCondTypeUik() . '  AND IkFullName IN (' . $this->_getWatchGateway()->getCondIn($resultType) .')' ;
    }

    /**
     * @return string
     */
    private function _getCondOfficial()
    {
        return $this->_getCondResultType(ParserIKData_Model_Protocol412::TYPE_OF);
    }

    private function _getCondResultType($resultType)
    {
        return 'ResultType = "'.$this->_escapeString($resultType).'"';
    }

    /**
     * @param int $uikNum
     * @return string
     */
    private function _getCondUik($uikNum)
    {
        return $this->_getCondTypeUik() . ' AND IkFullName = ' . intval($uikNum) ;
    }

    /**
     * @param string $okrugAbbr
     */
    private function _getCondOkrug($okrugAbbr)
    {
        $uikCond = $this->_getUikGateway()->getCondOkrug($okrugAbbr);
        return $this->_getCondTypeUik() . ' AND IkFullName IN ('.$uikCond.')';
    }

    /**
     * @param string $resultType
     * @return string
     */
    private function _getCondResultForType($resultType)
    {
        return $this->_getCondTypeUik()
            . ' AND
            	(
            		(' . $this->_getCondResultType($resultType) . ' ) OR
        			('.$this->_getCondOfficial().'
        				AND IkFullName NOT IN ('.$this->_getCondHasProtocol($resultType).')
        				AND IkFullName IN ('.$this->_getWatchGateway()->getCondIn($resultType).')
        			)
        		)';
    }

    /**
     * @param string $resultType
     * @return string
     */
    private function _getCondHasProtocol($resultType)
    {
        return ' SELECT IkFullName FROM '. $this->_table . ' WHERE '. $this->_getCondTypeUik() . ' AND ' . $this->_getCondResultType($resultType);
    }

    /**
     *  @return string
     */
    private function _getCondTypeUik()
    {
        return ' IkType = "'.$this->_escapeString(ParserIKData_Model_Protocol412::IkTYPE_UIK).'"';
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

    /**
     * @return ParserIKData_Gateway_Watch412
     */
    private function _getWatchGateway()
    {
        return new ParserIKData_Gateway_Watch412();
    }
}