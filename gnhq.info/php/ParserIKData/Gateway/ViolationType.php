<?php
class ParserIKData_Gateway_ViolationType extends ParserIKData_Gateway_Abstract
{
    private $_table = 'violation_type';
    private $_model = 'ParserIKData_Model_ViolationType';

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

    /**
     * @param ParserIKData_Model_ViolationType $violT
     */
    public function save($violT)
    {
        $this->_getDriver()->query($this->_insertQuery($violT));
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, $this->_model);
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
        		(MergedType, ProjectType,  ProjectCode, FullName)
          values (%d, "%s", "%s", "%s")',
          $data[0], $data[1], strtoupper(substr($data[2], 0, 2)), $data[3]);
        return $query;
    }
}