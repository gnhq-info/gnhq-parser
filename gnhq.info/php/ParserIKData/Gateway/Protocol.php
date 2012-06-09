<?php
/**
 * @author admin
 */
abstract class ParserIKData_Gateway_Protocol extends ParserIKData_Gateway_Abstract
{
    protected $_table = '';
    protected $_reservTable = '';
    protected $_modelClass = '';

    /**
    * @return int
    */
    abstract protected function _getLineAmount();

    /**
     * @return int
     */
    abstract protected function _getAllowableDiscrepancy();


    /**
     * @return int[]
     */
    abstract protected function _getIndicesForCompare();

    /**
     * @return ParserIKData_Gateway_Watch
     */
    protected function _getWatchGateway()
    {
        return new ParserIKData_Gateway_Watch403();
    }

    /**
     * @return ParserIKData_Gateway_UIKRussia
     */
    protected function _getUikGeneralGateway()
    {
        return new ParserIKData_Gateway_UIKRussia();
    }

    /**
    * @return string
    */
    abstract protected function _getCondControlRel();

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return null;
    }


    /**
    * @param ParserIKData_Model_Protocol $proto
    */
    public function insert($proto)
    {
        $this->_getDriver()->query($this->_insertQuery($proto, $this->_table));
        $this->_getDriver()->query($this->_insertQuery($proto, $this->_reservTable));
    }



    /**
     * @param ParserIKData_Model_Protocol $proto
     */
    public function update($proto) {
        $this->_getDriver()->query($this->_updateQuery($proto, $this->_table));
        $this->_getDriver()->query($this->_insertQuery($proto, $this->_reservTable));
    }

    /**
    * @param ParserIKData_Model_Protocol $proto
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
     * @param int $regionNum
     * @param string $okrugAbbr
     * @param int $tikNum
     * @param int $uikNum
     * @param string[] $resultType
     * @param int $constrolRelTrue
     * @return ParserIKData_Model_Protocol403|NULL
     */
    public function getMixedResult($regionNum = null, $okrugAbbr = null, $tikNum = null, $uikNum = null, $resultType = null,
        $controlRelTrue = false, $averageByUik = false )
    {
        $args = func_get_args();
        if (!is_array($resultType)) {
            $resultType = array($resultType);
        }
        if (false === ($result = $this->_loadFromCache(get_called_class(), __FUNCTION__, $args)) ) {
            $cond = $this->_buildCond($regionNum, $okrugAbbr, $tikNum, $uikNum, $resultType, $controlRelTrue);

            if ($averageByUik) {
                $query = $this->_buildAgjSumQuery($cond);
            } else {
                $query = $this->_buildSumQuery($cond);
            }
            $result = $this->_fetchSumProtocol($query);

            $this->_saveToCache(get_called_class(), __FUNCTION__, $args, $result);
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
            $maxAllowable = $this->_getAllowableDiscrepancy();
        }
        if (!$indices) {
            $indices = $this->_getIndicesForCompare();
        }
        $typeOf = ParserIKData_Model_Protocol::TYPE_OF;
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
     * @param string[] $projectCodes
     * @param int $regionNum
     * @param int $tikNum
     */
    public function getCondHasProto($projectCodes, $regionNum, $tikNum)
    {
        $conds = array();
        $conds[] = '1 = 1';
        if ($projectCodes) {
            $conds[] = $this->_getCondResultType($projectCodes);
        }
        if ($regionNum) {
            $conds[] = $this->_getCondRegion($regionNum);
            if ($tikNum) {
                $conds[] = $this->_getCondTik($regionNum, $tikNum);
            }
        }
        return ' IN (SELECT DISTINCT IkFullName FROM '.$this->_table.' WHERE '.implode(' AND ', $conds) . ' )';
    }

    /**
     * @param int $regionNum
     * @param string $okrugAbbr
     * @param int $uikNum
     * @param string[] $resultType
     * @param bool $controlRelTrue
     * @return string
     */
    protected function _buildCond($regionNum = null, $okrugAbbr = null, $tikNum = null, $uikNum = null, $resultType = null, $controlRelTrue = false)
    {
        $condParts = array();
        if (!$resultType) {
            $condParts[] = $this->_getCondOfficial();
        } else {
            // все результаты данного типа
            $condParts[] = $this->_getCondResultForType($resultType);
        }
        if ($regionNum) {
            if (is_array($regionNum)) {
                $condParts[] = $this->_getCondRegions($regionNum);
            } else {
                $condParts[] = $this->_getCondRegion($regionNum);
            }
        }

        if ($uikNum) {
            $condParts[] = $this->_getCondUik($uikNum);
        } elseif ($tikNum) {
            $condParts[] = $this->_getCondTik($regionNum, $tikNum);
        } elseif ($okrugAbbr) {
            $condParts[] = $this->_getCondOkrug($okrugAbbr);
        }

        if ($controlRelTrue) {
            $condParts[] = $this->_getCondControlRel();
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
    protected function _getCondLineDiscrepancy($tableAlias1, $tableAlias2, $num, $maxAllowable)
    {
        return 'ABS ('.$tableAlias1.'.Line'.$num.' - '.$tableAlias2.'.Line'.$num.') > ' .$maxAllowable;
    }


    /**
     * @param string $query
     * @return ParserIKData_Model_Protocol|NULL
     */
    protected function _fetchSumProtocol($query)
    {
        $result = $this->_getDriver()->query($query);
        if (!$result) {
            return null;
        }
        while ( ($data = $this->_fetchResultToArray($result)) !== false) {
            $count = array_pop($data);
            $protocol = call_user_func_array(array($this->_modelClass, 'fromArray'), array($data));
            $protocol->setUikCount($count);
            return $protocol;
        }
        return null;
    }

    /**
     * @param string $condString
     * @return string
     */
    protected function _buildSumQuery($condString)
    {
        return 'SELECT ' . $this->_getSumSelect() . ', COUNT(*) as Count  FROM ' . $this->_table . ' WHERE ' . $condString;
    }

    protected function _buildAgjSumQuery($condString)
    {
        $q = 'SELECT ' . $this->_getSumSelect() . ', COUNT(DISTINCT IkFullName) as COUNT FROM
        	(SELECT '.$this->_getAdjSumSelect().' FROM '.$this->_table . ' WHERE ' . $condString .' GROUP BY IkFullName) AS Average';
        return $q;
    }

    /**
    * @param string $condString
    * @return string
    */
    protected function _buildAllQuery($condString)
    {
        return 'SELECT * FROM ' . $this->_table . ' WHERE ' . $condString;
    }

    /**
     * @param string $resultType
     * @return string
     */
    protected function _getCondVyborka($resultType)
    {
        return $this->_getCondTypeUik() . '  AND IkFullName IN (' . $this->_getWatchGateway()->getCondIn($resultType) .')' ;
    }

    /**
     * @return string
     */
    protected function _getCondOfficial()
    {
        return $this->_getCondResultType(ParserIKData_Model_Protocol::TYPE_OF);
    }

    protected function _getCondResultType($resultType)
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
    protected function _getCondUik($uikNum)
    {
        return $this->_getCondTypeUik() . ' AND IkFullName = ' . intval($uikNum) ;
    }

    /**
    * @param int $tikNum
    * @return string
    */
    protected function _getCondTik($regionNum, $tikNum)
    {
        return $this->_getCondTypeUik() . ' AND IkFullName IN (' . $this->_getUikGeneralGateway()->getCondTik($regionNum, $tikNum) .')' ;
    }

    /**
     * @param string $okrugAbbr
     */
    protected function _getCondOkrug($okrugAbbr)
    {
        $uikCond = $this->_getUikGateway()->getCondOkrug($okrugAbbr);
        return $this->_getCondTypeUik() . ' AND IkFullName IN ('.$uikCond.')';
    }

    /**
     * @param int $regionNum
     */
    protected function _getCondRegion($regionNum)
    {
        return sprintf(' (IkFullName >= %d AND IkFullName < %d)',
            $regionNum*ParserIKData_Model_UIKRussia::MODULE,
            ($regionNum+1)*ParserIKData_Model_UIKRussia::MODULE);
    }

    /**
     * @param array $regionNums
     */
    protected function _getCondRegions($regionNums)
    {
        $condOrs = array();
        foreach ($regionNums as $regionNum) {
            $min = $regionNum*ParserIKData_Model_UIKRussia::MODULE;
            $max = ($regionNum+1)*ParserIKData_Model_UIKRussia::MODULE;
            $condOrs[] = '(IkFullName >= '.intval($min).' AND IkFullName < '.intval($max).')';
        }
        return implode(' OR ', $condOrs);
    }


    /**
     * @param string $resultType
     * @return string
     */
    protected function _getCondResultForType($resultType)
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
    protected function _getCondHasProtocol($resultType)
    {
        return ' SELECT IkFullName FROM '. $this->_table . ' WHERE '. $this->_getCondTypeUik() . ' AND ' . $this->_getCondResultType($resultType);
    }

    /**
     *  @return string
     */
    protected function _getCondTypeUik()
    {
        return ' IkType = "'.$this->_escapeString(ParserIKData_Model_Protocol::IkTYPE_UIK).'"';
    }

    /**
     * @return string
     */
    protected function _getSumSelect()
    {
        $statement = ' "Mixed" AS IkFullName, "Mixed" AS IkType, "Mixed" AS ResultType, SUM(ClaimCount) AS ClaimCount, "", "", "","","","" ';
        for ($i = 1; $i < $this->_getLineAmount(); $i++) {
            $statement .= ', SUM(Line' . $i . ') AS Line' . $i . PHP_EOL;
        }
        return $statement;
    }

    /**
    * @return string
    */
    protected function _getAdjSumSelect()
    {
        $statement = ' IkFullName, "Mixed" AS IkType, "Mixed" AS ResultType, SUM(ClaimCount)/Count(*) AS ClaimCount,
        	"" AS c1, "" AS c2, "" AS c3, "" AS c4, "" AS c5, ""  AS c6 ';
        for ($i = 1; $i < $this->_getLineAmount(); $i++) {
            $statement .= ', SUM(Line' . $i . ')/Count(*) AS Line' . $i . PHP_EOL;
        }
        return $statement;
    }


    /**
    * @param ParserIKData_Model_Protocol $proto
    * @param string $table
    * @return string
    */
    protected function _insertQuery($proto, $table)
    {
        $lineFields = array();
        for ($i = 1; $i < $this->_getLineAmount(); $i++) {
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
                $this->_escapeString(ParserIKData_Model_Protocol::IkTYPE_UIK),
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
     * @param ParserIKData_Model_Protocol $proto
     * @param string $table
     * @return string
     */
    protected function _updateQuery($proto, $table)
    {
        $lineData = $proto->getData();
        $lineParts = array();
        for ($i = 1; $i < $this->_getLineAmount(); $i++) {
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
}