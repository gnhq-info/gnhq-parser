<?php
class ParserIKData_Gateway_Violation extends ParserIKData_Gateway_Abstract
{
    private $_table = 'violation';
    private $_reservTable = 'violation_copy';

    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    /**
     * @param string $projectCode
     * @param string $projectId
     * @return ParserIKData_Model_Violation|null
     */
    public function find($projectCode, $projectId)
    {
        $whereCond = sprintf('ProjectCode = "%s" AND ProjectId = "%s"', $this->_escapeString($projectCode), $this->_escapeString($projectId));
        $data = $this->_loadFromTable($this->_table, 'ParserIKData_Model_Violation', $whereCond);
        if (count($data) == 0) {
            return null;
        } else {
            return $data[0];
        }
    }

    /**
     * @param ParserIKData_Model_Violation $viol
     */
    public function insert($viol)
    {
        $this->_getDriver()->query($this->_insertQuery($viol, $this->_table));
        $this->_getDriver()->query($this->_insertQuery($viol, $this->_reservTable));
    }



    /**
     * @param ParserIKData_Model_Violation $viol
     */
    public function update($viol) {
        $this->_getDriver()->query($this->_updateQuery($viol, $this->_table));
        $this->_getDriver()->query($this->_insertQuery($viol, $this->_reservTable));
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, 'ParserIKData_Model_Violation');
    }

    /**
     * @param ParserIKData_Model_Violation $viol
     * @param string $table
     * @return string
     */
    private function _insertQuery($viol, $table)
    {
        $data = $viol->toArray();
        $data = $this->_getDriver()->escapeArray($data);
        $query = sprintf('insert into '.$table.'
        	(
        		ProjectId, ProjectCode,  ProjectUptime, ProjectVersion, RegionNum,
        		MergedTypeId, Description, Place, ComplaintStatus, UIKNum,
        		TIKNum, Media, Obsrole, Impact, Obstime,
        		Loadtime, Recchanel, Hqcomment, Obsid, Obstrusted,
        		PoliceReaction, Rectified, Rectime)
          values
          	(
          		"%s", "%s", "%s", %d, %d,
          		%d, "%s", "%s", "%s", %d,
          		%d, "%s", %d, %d, %s,
          		NOW(), %d, "%s", "%s", %d,
          		%d, %d, %s)',
            $data[0], $data[1], $data[2], $data[3], $data[4],
            $data[5], $data[6], $data[7], $data[8], $data[9],
            $data[10], $data[11], $data[12], $data[13], ($data[14] ? '"'.$data[14]. '"' : 'NULL'),
            $data[16], $data[17], $data[18], $data[19],
            $data[20], $data[21], ($data[22] ? '"'.$data[22]. '"' : 'NULL')
        );
        return $query;
    }


    /**
    * @param ParserIKData_Model_Violation $viol
    * @param string $table
    * @return string
    */
    private function _updateQuery($viol, $table)
    {
        $data = $viol->toArray();
        $data = $this->_getDriver()->escapeArray($data);
        $query = sprintf('UPDATE '.$table.' SET
				ProjectUptime = "%s",
				ProjectVersion = %d,
				RegionNum = %d,
				MergedTypeId = %d,
				Description = "%s",
				Place = "%s",
				ComplaintStatus = "%s",
				UIKNum = %d,
				TIKNum = %d,
				Media = "%s",
				Obsrole = %d,
				Impact = %d,
				Obstime = %s,
				Loadtime = NOW(),
				Recchanel = %d,
				Hqcomment = "%s",
				Obsid = "%s",
				Obstrusted = %d,
				PoliceReaction = %d,
				Rectified = %d,
				Rectime = %s
        	WHERE
        		ProjectId = "%s" AND ProjectCode = "%s"',
            $data[2], $data[3], $data[4], $data[5], $data[6],
            $data[7], $data[8], $data[9], $data[10], $data[11],
            $data[12], $data[13], ($data[14] ? '"'.$data[14]. '"' : 'NULL'), $data[16],
            $data[17], $data[18], $data[19], $data[20], $data[21],
            ($data[22] ? '"'.$data[22]. '"' : 'NULL'),
        $data[0], $data[1]);
        return $query;
    }

    private function _formWhere($projectCode, $notProjectCode, $mergedTypeId, $regionNum, $tikNum, $uikNum)
    {
        $parts = array();
        if ($projectCode) {
            $parts[] = $this->_getCondProjectCode($projectCode, false);
        }
        if ($notProjectCode) {
            $parts[] = $this->_getCondProjectCode($notProjectCode, true);
        }
        if ($mergedTypeId) {
            $parts[] = $this->_getCondType($mergedTypeId);
        }
        if ($regionNum) {
            $parts[] = $this->_getCondRegion($regionNum);
        }
        if ($tikNum) {
            $parts[] = $this->_getCondTik($tikNum);
        }
        if ($uikNum) {
            $parts[] = $this->_getCondUik($uikNum);
        }
        if (!$parts) {
            $parts[] = '1 = 1';
        }
        return '( ' . implode(') AND (', $parts) . ')';
    }

    /**
     * @param string $projectCode
     * @return string
     */
    private function _getCondProjectCode($projectCode, $not = false)
    {
        if ($projectCode == null) {
            return '1 = 1';
        }
        $sign = ($not ? '!=' : '=');
        return sprintf('ProjectCode '.$sign.' "%s"', $this->_escapeString($projectCode));
    }

    /**
     * @param int $mergeTypeId
     * @return string
     */
    private function _getCondType($mergedTypeId)
    {
        if ($mergedTypeId === null) {
            return '1 = 1';
        }
        return sprintf('MergedTypeId = %d', $mergedTypeId);
    }

    /**
     * @param int $regionNum
     * @return string
     */
    private function _getCondRegion($regionNum)
    {
        if ($regionNum === null) {
            return '1 = 1';
        }
        return sprintf('RegionNum = %d', $regionNum);
    }

    private function _getCondTik($tikNum)
    {
        if ($tikNum === null) {
            return '1 = 1';
        }
        return sprintf('TikNum = %d', $tikNum);
    }

    private function _getCondUik($uikNum)
    {
        if ($uikNum === null) {
            return '1 = 1';
        }
        return sprintf('UikNum = %d', $uikNum);
    }
}