<?php
class ParserIKData_Gateway_TIKRussia extends ParserIKData_Gateway_Abstract
{
    protected $_table = 'tik_russia';
    private $_modelClass = 'ParserIKData_Model_TIKRussia';

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

    /**
    * @param ParserIKData_Model_TIKRussia $tikR
    */
    public function update($tikR)
    {
        $this->_getDriver()->query($this->_updateQuery($tikR));
    }

    public function getForRegion($regionNum)
    {
        return $this->_loadFromTable($this->_table, $this->_modelClass, sprintf('RegionNum = %d', $regionNum), null, null);
    }

    public function getForRegionAndOkrug($regionNum, $okrug)
    {
        $args = func_get_args();
        if (false === ($result = $this->_loadFromCache(__CLASS__, __FUNCTION__, $args)) ) {
            $cond = sprintf('RegionNum = %d AND OkrugName = "%s"', $regionNum, $this->_getDriver()->escapeString($okrug));
            $result = $this->_loadFromTable($this->_table, $this->_modelClass, $cond, null, null);
            $this->_saveToCache(__CLASS__, __FUNCTION__, $args, $result);
        }
        return $result;
    }

    public function getAllByRegions()
    {
        return $this->_loadFromTable($this->_table, $this->_modelClass, null, null, 'RegionNum ASC, FullName ASC');
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, $this->_modelClass);
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

    /**
    * @param ParserIKData_Model_TIKRussia $tikR
    * @return string
    */
    private function _updateQuery($tikR)
    {
        $data = $tikR->toArray();
        $data = $this->_getDriver()->escapeArray($data);
        return sprintf('UPDATE '.$this->_table.' SET
        			FullName = "%s",
        			OkrugName = "%s",
        			Link = "%s"
        		WHERE
        			RegionNum = %d AND
        			TikNum = %d', $data[2], $data[3], $data[4], $data[0], $data[1]);
    }
}