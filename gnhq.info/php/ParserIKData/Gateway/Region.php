<?php
class ParserIKData_Gateway_Region extends ParserIKData_Gateway_Abstract
{
    protected $_table = 'region';

    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    /**
     * @param ParserIKData_Model_Region $region
     */
    public function save($region)
    {
        $this->_getDriver()->query($this->_insertQuery($region));
    }

    /**
    * @param ParserIKData_Model_Region $region
    */
    public function update($region)
    {
        $this->_getDriver()->query($this->_updateQuery($region));
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
        return sprintf('insert into '.$this->_table.' (RegionNum, FullName, Link, Population)
                	values(%d, "%s", "%s", %d)', $data[0], $data[1], $data[2], $data[3]);
    }

    /**
    * @param ParserIKData_Model_Region $region
    * @return string
    */
    private function _updateQuery($region)
    {
        return sprintf('UPDATE '.$this->_table.' SET
        		FullName   = "%s",
        		Link       = "%s",
        		Population = %d
        	WHERE
        		RegionNum = %d',
                $this->_escapeString($region->getFullName()),
                $this->_escapeString($region->getLink()),
                intval($region->getPopulation()),
                intval($region->getRegionNum())
        );
    }
}