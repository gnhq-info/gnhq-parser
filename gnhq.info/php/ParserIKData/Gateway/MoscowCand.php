<?php
class ParserIKData_Gateway_MoscowCand extends ParserIKData_Gateway_Abstract
{
    private $_table = 'moscow_municipal_cand';
    private $_modelClass = 'ParserIKData_Model_MoscowCand';


    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    /**
     * @param ParserIKData_Model_MoscowCand $cand
     */
    public function save($cand)
    {
        $this->_getDriver()->query($this->_insertQuery($cand));
    }

    /**
    * @param ParserIKData_Model_MoscowCand $cand
    */
    public function update($cand)
    {
        $this->_getDriver()->query($this->_updateQuery($cand));
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, $this->_modelClass);
    }


    /**
     * @param ParserIKData_Model_MoscowCand $cand
     * @return string
     */
    private function _insertQuery($cand)
    {
        return sprintf('insert into '.$this->_table.'
        		(OkrId, Num, FullName, IsBlacklist)
          values (%d, %d, "%s", %d)',
            intval($cand->getOkrId()),
            intval($cand->getNum()),
            $this->_escapeString($cand->getFullName()),
            $cand->isBlacklist() ? 1 : 0
        );
    }

    /**
    * @param ParserIKData_Model_MoscowCand $cand
    * @return string
    */
    private function _updateQuery($cand)
    {
        return sprintf('UPDATE '.$this->_table.' SET
            		IsBlacklist   = %d,
            	WHERE
            		OkrId = %d AND Num = %d',
            $cand->isBlacklist() ? 1 : 0,
            $cand->getOkrId(),
            $cand->getNum()
        );
    }
}