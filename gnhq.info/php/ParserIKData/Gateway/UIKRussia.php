<?php
class ParserIKData_Gateway_UIKRussia extends ParserIKData_Gateway_Abstract
{
    private $_table = 'uik_russia';

    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    /**
     * @param ParserIKData_Model_UIKRussia $uikR
     */
    public function save($uikR)
    {
        $this->_getDriver()->query($this->_insertQuery($uikR));
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, 'ParserIKData_Model_UIKRussia');
    }

    /**
     * @param ParserIKData_Model_UIKRussia $uikR
     * @return string
     */
    private function _insertQuery($uikR)
    {
        $data = $uikR->toArray();
        $data = $this->_getDriver()->escapeArray($data);
        return sprintf('insert into '.$this->_table.'
        		(RegionNum, TikNum,  UikNum, FullName, Link, Place, VotingPlace, BorderDescription)
          values (%d, %d, %d, %d, "%s", "%s", "%s", "%s")',
          $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);
    }
}