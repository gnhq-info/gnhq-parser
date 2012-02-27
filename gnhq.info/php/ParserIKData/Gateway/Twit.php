<?php
class ParserIKData_Gateway_Twit extends ParserIKData_Gateway_Abstract
{
    private $_table = 'twit';
    private $_modelClass = 'ParserIKData_Model_Twit';

    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    /**
     * @param ParserIKData_Model_Twit $twit
     */
    public function save($twit)
    {
        $this->_getDriver()->query($this->_insertQuery($twit));
    }

    public function getAll($amount)
    {
        return $this->_loadFromTable($this->_table, $this->_modelClass, null, '0, '.intval($amount), 'Time DESC');
    }

    public function findByGuids($guids)
    {
        if (!is_array($guids)) {
            $guids = array($guids);
        }
        $inParts = array();
        foreach ($guids as $guid) {
            $inParts[] = sprintf('"%s"', $this->_escapeString($guid));
        }
        $cond = 'Guid IN (' . implode(',', $inParts) . ')';
        return $this->_loadFromTable($this->_table, $this->_modelClass, $cond);
    }

    /**
     * @param ParserIKData_Model_Twit $twit
     * @return string
     */
    private function _insertQuery($tikR)
    {
        $data = $tikR->toArray();
        $data = $this->_getDriver()->escapeArray($data);
        return sprintf('insert into '.$this->_table.' (Guid, FullName, Description, Time, Link, Source, Place)
                	values("%s", "%s", "%s", "%s", "%s", "%s","%s")',
                    $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]
        );
    }
}