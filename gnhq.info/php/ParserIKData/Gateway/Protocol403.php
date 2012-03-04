<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol403 extends ParserIKData_Gateway_Abstract
{
    private $_table = 'result_403';
    private $_reservTable = 'result_403_copy';
    private $_modelClass = 'ParserIKData_Model_Protocol403';


    /**
    * @param ParserIKData_Model_Protocol403 $proto
    */
    public function insert($proto)
    {
        $this->_getDriver()->query($this->_insertQuery($proto, $this->_table));
        $this->_getDriver()->query($this->_insertQuery($proto, $this->_reservTable));
    }



    /**
     * @param ParserIKData_Model_Protocol403 $proto
     */
    public function update($proto) {
        $this->_getDriver()->query($this->_updateQuery($proto, $this->_table));
        $this->_getDriver()->query($this->_insertQuery($proto, $this->_reservTable));
    }

    /**
    * @param ParserIKData_Model_Protocol403 $proto
    */
    public function reserve($proto) {
        $this->_getDriver()->query($this->_insertQuery($proto, $this->_reservTable));
    }

    /**
     * @param string $projectCode
     * @return ParserIKData_Model[]|NULL
     */
    public function findForProject($projectCode)
    {
        return $this->_loadFromTable($this->_table, $this->_modelClass, sprintf('ResultType = "%s"', $projectCode));
    }

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 60;
    }

    /**
     * @param int $regionNum
     * @param string $okrugAbbr
     * @param int $uikNum
     * @param string[] $resultType
     * @param boolean $oProto    - только по протоколам проекта - имеет смысл только если $resultType != null
     * @param boolean $oClean    - только чистые - имеет смысл только если $oProto = true (на чистом участке должен быть протокол)
     * @param boolean $oDiscrep  - только c расхождениями - имеет смысл только если $oProto = true (на чистом участке должен быть протокол от ГН)
     * @param boolean $oReport   - только с отчетами
     * @return ParserIKData_Model_Protocol403|NULL
     */
    public function getMixedResult($regionNum = null, $okrugAbbr = null, $uikNum = null, $resultType = null, $oProto = false, $oClean = false, $oDiscrep = false, $oReport = false)
    {
        $args = func_get_args();
        if (!is_array($resultType)) {
            $resultType = array($resultType);
        }
        if (false === ($result = $this->_loadFromCache(__CLASS__, __FUNCTION__, $args)) ) {
            $cond = $this->_buildCond($regionNum, $okrugAbbr, $uikNum, $resultType, $oProto, $oClean, $oDiscrep, $oReport);
            $query = $this->_buildSumQuery($cond);
            $result = $this->_fetchSumProtocol($query);

            $this->_saveToCache(__CLASS__, __FUNCTION__, $args, $result);
        }

        return $result;
    }

    public function getCondResultType($resultType)
    {
        return 'SELECT IkFullName FROM '.$this->_table.' WHERE IkType = "UIK" AND '. $this->_getCondResultType($resultType);
    }

    public function getCondDiscrepancy($watchType = null, $maxAllowable = null, $indices = null)
    {
        if (!$maxAllowable) {
            $maxAllowable = ParserIKData_Model_Protocol403::ALLOWABLE_DISCREPANCY;
        }
        if (!$indices) {
            $indices = ParserIKData_Model_Protocol403::getIndicesForCompare();
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
     * @param int $regionNum
     * @param string $okrugAbbr
     * @param int $uikNum
     * @param string[] $resultType
     * @param boolean $oProto    - только по протоколам проекта - имеет смысл только если $resultType != null
     * @param boolean $oClean    - только чистые - имеет смысл только если $oProto = true (на чистом участке должен быть протокол)
     * @param boolean $oDiscrep  - только c расхождениями - имеет смысл только если $oProto = true (на чистом участке должен быть протокол от ГН)
     * @param boolean $oReport   - только с отчетами
     * @return string
     */
    private function _buildCond($regionNum = null, $okrugAbbr = null, $uikNum = null, $resultType = null, $oProto = false, $oClean = false, $oDiscrep = false, $oReport = false)
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
        if ($regionNum) {
            $condParts[] = $this->_getCondRegion($regionNum);
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
     * @return ParserIKData_Model_Protocol403|NULL
     */
    private function _fetchSumProtocol($query)
    {
        $result = $this->_getDriver()->query($query);
        if (!$result) {
            return null;
        }
        while ( ($data = $this->_fetchResultToArray($result)) !== false) {
            $count = array_pop($data);
            $protocol = ParserIKData_Model_Protocol403::fromArray($data);
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
        return $this->_getCondResultType(ParserIKData_Model_Protocol403::TYPE_OF);
    }

    private function _getCondResultType($resultType)
    {
        if (!is_array($resultType)) {
            $resultType = array($resultType);
        }
        $parts = array();
        foreach ($resultType as $i => $rType) {
            $parts[] = $this->_escapeString($rType);
        }
        $cond = 'ResultType IN ("' . implode('", "', $parts) . '")';
        return $cond;
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
     * @param int $regionNum
     */
    private function _getCondRegion($regionNum)
    {
        return sprintf(' (IkFullName >= %d AND IkFullName < %d)',
            $regionNum*ParserIKData_Model_UIKRussia::MODULE,
            ($regionNum+1)*ParserIKData_Model_UIKRussia::MODULE);
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
        return ' IkType = "'.$this->_escapeString(ParserIKData_Model_Protocol403::IkTYPE_UIK).'"';
    }

    /**
     * @return string
     */
    private function _getSumSelect()
    {
        $statement = ' "Mixed" AS IkFullName, "Mixed" AS IkType, "Mixed" AS ResultType, SUM(ClaimCount) AS ClaimCount, "", "", "","","","" ';
        for ($i = 1; $i < ParserIKData_Model_Protocol403::LINE_AMOUNT; $i++) {
            $statement .= ', SUM(Line' . $i . ') AS Line' . $i . PHP_EOL;
        }
        return $statement;
    }


    /**
    * @param ParserIKData_Model_Protocol403 $proto
    * @param string $table
    * @return string
    */
    private function _insertQuery($proto, $table)
    {
        $lineFields = array();
        for ($i = 1; $i < ParserIKData_Model_Protocol403::LINE_AMOUNT; $i++) {
            $lineFields[] = 'Line' . $i;
        }
        $lineFields = implode(', ', $lineFields);
        $lineData = $proto->getData();
        foreach ($lineData as $l => $v) {
            $lineData[$l] = intval($v);
        }
        $lineData = implode(', ', $lineData);

        $query = sprintf('insert into '.$table.'
            	(
            		IKFullName, IkType, ResultType, ClaimCount, ProjectId,
            		UpdateTime, SignTime, LoadTime, Dirt, HasCopy, Revised, %s)
              values
              	(
              		%d, "%s", "%s", %d, "%s",
              		"%s", %s, NOW(), %d, %d, %d, %s)',
              	$lineFields,
                $proto->getIkFullName(),
                $this->_escapeString(ParserIKData_Model_Protocol403::IkTYPE_UIK),
                $this->_escapeString($proto->getResultType()),
                $proto->getClaimCount(),
                $this->_escapeString($proto->getProjectId()),
                $this->_escapeString($proto->getUpdateTime()),
                ($proto->getSignTime() ? '"'.$this->_escapeString($proto->getSignTime()).'"' : 'NULL'),
                $proto->getDirt(),
                $proto->getCopy(),
                $proto->getRevised(),
                $lineData
            );
        return $query;
    }


    /**
     * @param ParserIKData_Model_Protocol403 $proto
     * @param string $table
     * @return string
     */
    private function _updateQuery($proto, $table)
    {
        $lineData = $proto->getData();
        $lineParts = array();
        for ($i = 1; $i < ParserIKData_Model_Protocol403::LINE_AMOUNT; $i++) {
            $lineParts[] = 'Line' . $i . ' = '.intval($lineData[$i]);
        }
        $linePart = implode (', '.PHP_EOL, $lineParts);
        $query = sprintf('UPDATE '.$table.' SET
    				ClaimCount = %d,
    				ProjectId = "%s",
    				UpdateTime = "%s",
    				SignTime = %s,
    				LoadTime = NOW(),
    				Dirt = %d,
    				HasCopy = %d,
    				Revised = %d,
    				%s
            	WHERE
            		IKFullName = %d AND ResultType = "%s" AND IkType = "UIK"',
                $proto->getClaimCount(),
                $this->_escapeString($proto->getProjectId()),
                $this->_escapeString($proto->getUpdateTime()),
                $proto->getSignTime() ? '"'.$this->_escapeString($proto->getSignTime()).'"' : 'NULL',
                $proto->getDirt(),
                $proto->getCopy(),
                $proto->getRevised(),
                $linePart,
                $proto->getIkFullName(),
                $proto->getResultType()
        );
        return $query;
    }



    /**
     * @return ParserIKData_Gateway_Watch403
     */
    protected function _getWatchGateway()
    {
        return new ParserIKData_Gateway_Watch403();
    }
}