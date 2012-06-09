<?php
class ParserIKData_Gateway_Okrug extends ParserIKData_Gateway_Abstract
{
    private $_view_uik_okrug = 'uik_okrug';
    private $_table = 'okrug';

    /**
     * @return array
     */
    public function getDiscrepancyCount()
    {
        $args = func_get_args();
        if (false === ($data = $this->_loadFromCache(get_called_class(), __FUNCTION__, $args)) ) {
            $result = $this->_getDriver()->selectAssoc(
        		'okrug, count(*) as DiscrCount',
                $this->_view_uik_okrug,
            	'uik IN ('.$this->_getProtocolGateway()->getCondDiscrepancy(). ')',
                null,
                null,
        		'okrug'
                );
            foreach ($result as $i => $row) {
                $data[$row['okrug']] = $row['DiscrCount'];
            }

            $this->_saveToCache(get_called_class(), __FUNCTION__, $args, $data);
        }
        return $data;
    }

    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    public function save($okrug)
    {
        $this->_getDriver()->query($this->_insertQuery($okrug));
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, 'ParserIKData_Model_Okrug');
    }

    private function _insertQuery($okrug)
    {
        $data = $okrug->toArray();
        $data = $this->_getDriver()->escapeArray($data);
        return sprintf('insert into '.$this->_table.' (Abbr, FullName, Link, TikDataLink)
                	values("%s", "%s", "%s", "%s")', $data[0], $data[1], $data[2], $data[3]);
    }
}