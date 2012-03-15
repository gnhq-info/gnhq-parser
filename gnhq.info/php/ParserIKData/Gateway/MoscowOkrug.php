<?php
class ParserIKData_Gateway_MoscowOkrug extends ParserIKData_Gateway_Abstract
{
    private $_table = 'moscow_municipal_oiks';
    private $_modelClass = 'ParserIKData_Model_MoscowOkrug';


    public function removeAll()
    {
        $this->_getDriver()->truncateTable($this->_table);
    }

    /**
     * @param ParserIKData_Model_MoscowOkrug $okr
     */
    public function save($okr)
    {
        $this->_getDriver()->query($this->_insertQuery($okr));
    }

    /**
    * @param ParserIKData_Model_MoscowOkrug $okr
    */
    public function update($okr)
    {
        $this->_getDriver()->query($this->_updateQuery($okr));
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, $this->_modelClass);
    }

    /**
     * @return ParserIKData_Model_MoscowOkrug[]
     */
    public function getAllById()
    {
        $okrugs = $this->getAll();
        $res = array();
        foreach ($okrugs as $okr) {
            $res[$okr->getId()] = $okr;
        }
        return $res;
    }



    /**
     * @param ParserIKData_Model_MoscowOkrug $okr
     * @return string
     */
    private function _insertQuery($okr)
    {
        return sprintf('insert into '.$this->_table.'
        		(Id, FullName,  TikNum, Link, Magnitude)
          values (%d, "%s", %d, "%s", %d)',
            intval($okr->getId()),
            $this->_escapeString($okr->getFullName()),
            intval($okr->getTikNum()),
            $this->_escapeString($okr->getLink()),
            intval($okr->getMagnitude()));
    }

    /**
    * @param ParserIKData_Model_MoscowOkrug $okr
    * @return string
    */
    private function _updateQuery($okr)
    {
        return sprintf('UPDATE '.$this->_table.' SET
            		FullName   = "%s",
            		Link       = "%s",
            		Magnitude  = %d
            	WHERE
            		Id = %d',
            $this->_escapeString($okr->getFullName()),
            $this->_escapeString($okr->getLink()),
            intval($okr->getMagnitude()),
            intval($okr->getId())
        );
    }
}