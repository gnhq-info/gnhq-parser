<?php
class ParserIKData_Gateway_TIKRussia extends ParserIKData_Gateway_Abstract
{
    private $_table = 'tik_russia';

    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    /**
     * @param ParserIKData_Model_TIKRussia $tikR
     */
    public function save($tikR)
    {
        $this->_getDriver()->query($this->_insertQuery($tikR));
    }

    public function getAllByRegions()
    {
        return $this->_loadFromTable($this->_table, 'ParserIKData_Model_TIKRussia', null, null, 'RegionNum ASC, TikNum ASC');
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, 'ParserIKData_Model_TIKRussia');
    }

    /**
     * @param ParserIKData_Model_TIKRussia $tikR
     * @return string
     */
    private function _insertQuery($tikR)
    {
        $data = $tikR->toArray();
        $data = $this->_getDriver()->escapeArray($data);
        return sprintf('insert into '.$this->_table.' (RegionNum, TikNum, FullName, OkrugName, Link)
                	values(%d, %d, "%s", "%s", "%s")', $data[0], $data[1], $data[2], $data[3], $data[4]);
    }
}