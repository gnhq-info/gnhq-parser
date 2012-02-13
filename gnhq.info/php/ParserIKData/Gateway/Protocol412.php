<?php
class ParserIKData_Gateway_Protocol412 extends ParserIKData_Gateway_Abstract
{
    private $_table = 'result_412';


    /**
     * @param string $okrugAbbr
     * @param int $uikNum
     * @param string $resultType
     * @param boolean $oProto    - только по протоколам проекта - имеет смысл только если $resultType != null
     * @param boolean $oClean    - только чистые - имеет смысл только если $oProto = true (на чистом участке должен быть протокол)
     * @param boolean $oDiscrep  - только c расхождениями - имеет смысл только если $oProto = true (на чистом участке должен быть протокол от ГН)
     * @param boolean $oReport   - только с отчетами
     * @return ParserIKData_Model_Protocol412|NULL
     */
    public function getMixedResult($okrugAbbr = null, $uikNum = null, $resultType = null, $oProto = false, $oClean = false, $oDiscrep = false, $oReport = false)
    {
        $cond = $this->_buildCond($okrugAbbr, $uikNum, $resultType, $oProto, $oClean, $oDiscrep, $oReport);
        $query = $this->_buildSumQuery($cond);
        return $this->_fetchSumProtocol($query);
    }

    public function getCondResultType($resultType)
    {
        return 'SELECT IkFullName FROM '.$this->_table.' WHERE IkType = "UIK" AND '. $this->_getCondResultType($resultType);
    }

    public function getCondDiscrepancy($watchType = null, $maxAllowable = null, $indices = null)
    {
        if ($watchType !== ParserIKData_Model_Protocol412::TYPE_GN) {
            $watchType = ParserIKData_Model_Protocol412::TYPE_GN;
        }
        if (!$maxAllowable) {
            $maxAllowable = ParserIKData_Model_Protocol412::ALLOWABLE_DISCREPANCY;
        }
        if (!$indices) {
            $indices = ParserIKData_Model_Protocol412::getIndicesForCompare();
        }
        $typeOf = 'OF';
        $conds = array();
        foreach ($indices as $ind) {
            $conds[] = $this->_getCondLineDiscrepancy($typeOf, $watchType, $ind, $maxAllowable);
        }
        $cond = '(' . implode(') OR ' . PHP_EOL . ' (', $conds) . ')';
        return 'SELECT ' . $typeOf . '.IkFullName FROM `'.$this->_table.'` AS `'.$typeOf.'` INNER JOIN `'.$this->_table.'` AS `'.$watchType.'`
			ON
				'.$watchType.'.IkFullName = '.$typeOf.'.IkFullName AND
				'.$watchType.'.IkType = '.$typeOf.'.IkType AND
				'.$watchType.'.ResultType = "'.$this->_escapeString($watchType).'" AND
				'.$typeOf.'.ResultType = "'.$this->_escapeString($typeOf).'"
			WHERE ' . $cond;
    }

    /**
    * @param string $okrugAbbr
    * @param int $uikNum
    * @param string $resultType
    * @param boolean $oProto    - только по протоколам проекта - имеет смысл только если $resultType != null
    * @param boolean $oClean    - только чистые - имеет смысл только если $oProto = true (на чистом участке должен быть протокол)
    * @param boolean $oDiscrep  - только c расхождениями - имеет смысл только если $oProto = true (на чистом участке должен быть протокол от ГН)
    * @param boolean $oReport   - только с отчетами
    * @return string
    */
    private function _buildCond($okrugAbbr = null, $uikNum = null, $resultType = null, $oProto = false, $oClean = false, $oDiscrep = false, $oReport = false)
    {
        $condParts = array();
        if (!$resultType) {
            $condParts[] = $this->_getCondOfficial();
        } else {
            if ($oProto == false) {
                // все результаты данного типа (не важно - есть или нет протокол, чистый ли участок или нет
                $condParts[] = $this->_getCondResultForType($resultType);
            } else {
                // используем только протоколы данного типа
                $condParts[] = $this->_getCondResultType($resultType);
                if ($oClean) {
                    // используем только протоколы данного типа по чистым участкам (если нет протокола - грубое нарушение - не может быть чистым)
                    $condParts[] = 'IkFullName IN (' . $this->_getWatchGateway()->getCondClear($resultType) . ')';
                } elseif ($oDiscrep) {
                    $condParts[] = 'IkFullName IN (' . $this->getCondDiscrepancy($resultType) . ')';
                }
            }
            if ($oReport) {
                $condParts[] = 'IkFullName IN (' . $this->_getReportGateway()->getCondWithReport($resultType)  . ')';
            }
        }
        if ($okrugAbbr) {
            $condParts[] = $this->_getCondOkrug($okrugAbbr);
        } elseif ($uikNum) {
            $condParts[] = $this->_getCondUik($uikNum);
        }
        $cond = '( ' . implode( ' ) AND ( ', $condParts) . ' )';
        return $cond;
    }

    /**
     * @param string $tableAlias1
     * @param string $tableAlias2
     * @param int $num
     * @param int $maxAllowable
     * @return string
     */
    private function _getCondLineDiscrepancy($tableAlias1, $tableAlias2, $num, $maxAllowable)
    {
        return 'ABS ('.$tableAlias1.'.Line'.$num.' - '.$tableAlias2.'.Line'.$num.') > ' .$maxAllowable;
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
            $count = array_pop($data);
            $protocol = ParserIKData_Model_Protocol412::fromArray($data);
            $protocol->setUikCount($count);
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
        return 'SELECT ' . $this->_getSumSelect() . ', COUNT(DISTINCT IkFullName) as Count  FROM ' . $this->_table . ' WHERE ' . $condString;
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
}