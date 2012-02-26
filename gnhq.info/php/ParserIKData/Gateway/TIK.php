<?php
class ParserIKData_Gateway_TIK extends ParserIKData_Gateway_Abstract
{
    private $_table = 'tik';
    private $_modelClass = 'ParserIKData_Model_TIK';

    /**
     * @param ParserIKData_Model_Okrug $okrug
     * @return ParserIKData_Model_TIK[]
     */
    public function getForOkrug($okrug)
    {
        $query = 'SELECT * FROM ' . $this->_table . ' WHERE ' . $this->_table . '.uid IN (' . $this->getCondOkrug($okrug->getAbbr()) . ')';
        $result = $this->_getDriver()->query($query);
        $tiks = array();
        while ( ($data = $this->_fetchResultToArray($result)) !== false) {
            $tik = ParserIKData_Model_TIK::fromArray($data);
            $tiks[$tik->getUniqueId()] = $tik;
        }
        return $tiks;
    }


    public function getCondOkrug($okrugAbbr)
    {
        return 'SELECT uid FROM ' . $this->_table . ' WHERE ' . $this->_table . '.OkrugAbbr = "' . $this->_escapeString($okrugAbbr) . '"';
    }

    public function getAll()
    {
        return $this->_loadFromTable($this->_table, $this->_modelClass);
    }
}