<?php
class ParserIKData_Gateway_ViolationType extends ParserIKData_Gateway_Abstract
{
    private $_table = 'violation_type';
    private $_model = 'ParserIKData_Model_ViolationType';

    /**
     * @return null|int
     */
    protected function _getCacheLifetime()
    {
        return 86400;
    }

    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    public function removeForProject($projectCode)
    {
        $this->_getDriver()->query(
        sprintf('DELETE FROM '.$this->_table . ' WHERE ProjectCode = "%s"', $this->_getDriver()->escapeString($projectCode) )
        );
    }

    public function findByProjectData($projectCode, $projectType)
    {
        $whereCond = sprintf (
            'ProjectCode = "%s" AND ProjectType = "%s"',
        $this->_escapeString($projectCode),
        $this->_escapeString($projectType)
        );

        $data = $this->_loadFromTable($this->_table, $this->_model, $whereCond);
        if (count($data) > 0) {
            return $data[0];
        } else {
            return null;
        }
    }

    public function findMergedTypeByProjectType($projectCode, $projectType)
    {
        $data = $this->getMergedTypesByProjectTypes($projectCode);
        if (!isset($data[$projectType])) {
            return ParserIKData_Model_ViolationType::DEFAULT_MTYPE;
        } else {
            return $data[$projectType];
        }
    }

    public function getMergedTypesByProjectTypes($projectCode)
    {
        $args = func_get_args();
        if (false === ($codes = $this->_loadFromCache(__CLASS__, __FUNCTION__, $args)) ) {
            $whereCond = sprintf ('ProjectCode = "%s"', $this->_escapeString($projectCode));

            $codes = array();
            $data = $this->_loadFromTable($this->_table, $this->_model, $whereCond);
            foreach ($data as $vType) {
                /* @var $vType ParserIKData_Model_ViolationType */
                $codes[$vType->getProjectType()] = $vType->getMergedType();
            }
            $this->_saveToCache(__CLASS__, __FUNCTION__, $args, $codes);

            //print 'not from cache'.PHP_EOL;
        } else {
            //print 'from cache'.PHP_EOL;
        }

        return $codes;
    }


    /**
     * @param ParserIKData_Model_ViolationType $violT
     */
    public function save($violT)
    {
        $this->_getDriver()->query($this->_insertQuery($violT));
    }

    public function getAllDistinct($order = null)
    {
        switch ($order) {
            case 'name':
                $order = 'FullName ASC';
                break;
            case 'id':
                $order = 'MergedType ASC';
                break;
            default:
                $order = null;
            break;
        }
        $args = func_get_args();
        if (false === ($result = $this->_loadFromCache(__CLASS__, __FUNCTION__, $args)) ) {
            $result = $this->_loadFromTable($this->_table, $this->_model, 'FullName != ""', null, $order);
            $this->_saveToCache(__CLASS__, __FUNCTION__, $args, $result);
        }
        return $result;
    }

    /**
     * @param ParserIKData_Model_ViolationType $violT
     * @return string
     */
    private function _insertQuery($violT)
    {
        $data = $violT->toArray();
        $data = $this->_getDriver()->escapeArray($data);
        $query = sprintf('insert into '.$this->_table.'
        		(MergedType, ProjectType,  ProjectCode, FullName, GroupType, Severity)
          values (%d, "%s", "%s", "%s", %d, %d)',
        $data[0], $data[1], strtoupper(substr($data[2], 0, 2)), $data[3], $data[4], $data[5]);
        return $query;
    }
}