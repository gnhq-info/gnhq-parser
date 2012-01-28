<?php
class ParserIKData_Warehouse_MySql implements ParserIKData_Warehouse_Interface
{
    /**
     * @var Lib_Db_MySql
     */
    private $_mysql = null;
    /**
     * @var Lib_Config_Interface
     */
    private $_mysqlConfig = null;

    /**
    * @return ParserIKData_Warehouse_Interface
    */
    public function saveAllOkrugs()
    {
        $okrugs = ParserIKData_Model_Okrug::getAllOBjects();
        $this->_mysql->truncateTable($this->_getOkrugTable());
        foreach ($okrugs as $okrug) {
            $this->_mysql->query($this->_insertOkrugQuery($okrug));
        }
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllOkrugs()
    {
        $this->_loadFromTable($this->_getOkrugTable(), 'ParserIKData_Model_Okrug');
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function saveAllTiks()
    {
        $tiks = ParserIKData_Model_TIK::getAllOBjects();
        $this->_mysql->truncateTable($this->_getTikTable());
        foreach ($tiks as $tik) {
            $this->_mysql->query($this->_insertTIKQuery($tik));
        }
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllTiks()
    {
        $this->_loadFromTable($this->_getTikTable(), 'ParserIKData_Model_TIK');
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function saveAllUiks()
    {
        $uiks = ParserIKData_Model_UIK::getAllOBjects();
        $this->_mysql->truncateTable($this->_getUikTable());
        foreach ($uiks as $uik) {
            $this->_mysql->query($this->_insertUIKQuery($uik));
        }
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllUiks()
    {
        $this->_loadFromTable($this->_getUikTable(), 'ParserIKData_Model_UIK');
        return $this;
    }

    /**
    * @return ParserIKData_Warehouse_Interface
    */
    public function saveElectionResults($electionCode, $resultType)
    {
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadElectionResults($electionCode, $resultType)
    {
        return $this;
    }


    /**
     * @param string $table
     * @param string $modelClass
     * @return ParserIKData_Warehouse_MySql
     */
    private function _saveToTable($table, $modelClass)
    {
        return $this;
    }

    /**
     * @param ParserIKData_Model_Okrug $okrug
     * @return string
     */
    private function _insertOkrugQuery($okrug)
    {
        $data = $okrug->toArray();
        $data = $this->_escapeArray($data);
        return sprintf('insert into '.$this->_getOkrugTable().' (Abbr, FullName, Link, TikDataLink)
        	values("%s", "%s", "%s", "%s")', $data[0], $data[1], $data[2], $data[3]);
    }

    /**
    * @param ParserIKData_Model_TIK $tik
    * @return string
    */
    private function _insertTIKQuery($tik)
    {
        $data = $tik->toArray();
        $data = $this->_escapeArray($data);
        return sprintf('insert into '.$this->_getTikTable().
        	' (OkrugAbbr, FullName, Address, Phone, Chief, Deputy, Secretary, Members, SelfInfoLink, AddressLink, SostavLink, Link)
            values("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")',
            $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11]
        );
    }

    /**
    * @param ParserIKData_Model_UIK $uik
    * @return string
    */
    private function _insertUIKQuery($uik)
    {
        $data = $uik->toArray();
        $data = $this->_escapeArray($data);
        return sprintf('insert into '.$this->_getUikTable().
            	' (TikUniqueId, FullName, BorderDescription, Place, VotingPlace, Link)
                values("%s", "%s", "%s", "%s", "%s", "%s")',
                $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]
        );
    }

    /**
     * @param string $fileName
     * @param string $modelClass
     * @return multitype:NULL
     */
    private function _loadFromTable($tableName, $modelClass)
    {
        $dbRes = $this->_mysql->select('*', $tableName);
        while ($arr = mysql_fetch_row($dbRes)) {
            $result[] = $modelClass::fromArray($arr);
        }
        return $result;
    }

    /**
     * @return string
     */
    private function _getOkrugTable()
    {
        return 'okrug';
    }

    /**
     * @return string
     */
    private function _getTikTable()
    {
        return 'tik';
    }

    /**
     * @param string $electionCode
     * @param string $resultType
     * @return string
     */
    private function _getElectionResultsTable($electionCode, $resultType)
    {
        return 'result_'.$electionCode.'_'.$resultType;
    }

    /**
    * @return string
    */
    private function _getUikTable()
    {
        return 'uik';
    }

    /**
     * @param array $array
     * @return string
     */
    private function _escapeArray($array)
    {
        foreach ($array as $k => $v) {
            $array[$k] = mysql_real_escape_string($v);
        }
        return $array;
    }

    /**
     * @param ParserIKData_ServiceLocator $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        $this->_mysql = $serviceLocator->getMySql();
        $this->_mysqlConfig = $serviceLocator->getMySqlConfig();
        $this->_mysql->selectDb($this->_mysqlConfig->getValue('db'));
    }

}