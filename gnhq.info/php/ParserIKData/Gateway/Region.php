<?php
class ParserIKData_Gateway_Region extends ParserIKData_Gateway_Abstract
{
    private $_table = 'region';

    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    /**
     * @param ParserIKData_Model_Region $okrug
     */
    public function save($region)
    {
        $this->_getDriver()->query($this->_insertQuery($region));
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, 'ParserIKData_Model_Region');
    }

    /**
     * @param ParserIKData_Model_Region $region
     * @return string
     */
    private function _insertQuery($region)
    {
        $data = $region->toArray();
        $data = $this->_getDriver()->escapeArray($data);
        return sprintf('insert into '.$this->_table.' (RegionNum, FullName, Link)
                	values(%d, "%s", "%s")', $data[0], $data[1], $data[2]);
    }
}