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
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllTiks()
    {
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function saveAllUiks()
    {
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllUiks()
    {
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
        	values("%s", "%s", "%s", "%s")', $data[0], $data['FullName'], $data['Link'], $data['TikDataLink']);
    }

    /**
     * @param string $fileName
     * @param string $modelClass
     * @return multitype:NULL
     */
    private function _loadFromTable($tableName, $modelClass)
    {
        $file = fopen($fileName, 'rb+');
        if (!$file) {
            die('cant read from file');
        }
        $result = array();
        while ( ($arr = fgetcsv($file, null, self::DELIMETER, self::ENCLOSURE)) !== false) {
            $result[] = $modelClass::fromArray($arr);
        }
        fclose($file);
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