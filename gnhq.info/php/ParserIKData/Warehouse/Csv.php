<?php
class ParserIKData_Warehouse_Csv implements ParserIKData_Warehouse_Interface
{
    const DELIMETER = ';';
    const ENCLOSURE = '"';
    /**
    * @return ParserIKData_Warehouse_Interface
    */
    public function saveAllOkrugs()
    {
        return $this->_saveToFile($this->_getOkrugFile(), 'ParserIKData_Model_Okrug');
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllOkrugs()
    {
        $this->_loadFromFile($this->_getOkrugFile(), 'ParserIKData_Model_Okrug');
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function saveAllTiks()
    {
        return $this->_saveToFile($this->_getTikFile(), 'ParserIKData_Model_TIK');
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllTiks()
    {
        $this->_loadFromFile($this->_getTikFile(), 'ParserIKData_Model_TIK');
        return $this;
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function saveAllUiks()
    {
        return $this->_saveToFile($this->_getUikFile(), 'ParserIKData_Model_UIK');
    }

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllUiks()
    {
        $this->_loadFromFile($this->_getUikFile(), 'ParserIKData_Model_UIK');
        return $this;
    }


    /**
     * @param string $fileName
     * @param string $modelClass
     * @return ParserIKData_Warehouse_Csv
     */
    private function _saveToFile($fileName, $modelClass)
    {
        $file = fopen($fileName, 'wb+');
        if (!$file) {
            die('cant write to file');
        }
        foreach ($modelClass::getAllOBjects() as $model) {
            /* @var $model ParserIKData_Model */
            fputcsv($file, $model->toArray(), self::DELIMETER, self::ENCLOSURE);
        }
        fclose($file);
        return $this;
    }

    /**
     * @param string $fileName
     * @param string $modelClass
     * @return multitype:NULL
     */
    private function _loadFromFile($fileName, $modelClass)
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
    private function _getOkrugFile()
    {
        return $this->_getDirectory() . 'okrug.csv';
    }

    /**
     * @return string
     */
    private function _getTikFile()
    {
        return $this->_getDirectory() . 'tik.csv';
    }

    /**
    * @return string
    */
    private function _getUikFile()
    {
        return $this->_getDirectory() . 'uik.csv';
    }

    /**
     * @return string
     */
    private function _getDirectory()
    {
        $dir = __DIR__;
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);
        if (substr($dir, -1 * strlen('Warehouse')) == 'Warehouse') {
            $dir = substr($dir, 0, strlen($dir) - strlen('Warehouse'));
        }
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);

        $dir = $dir . DIRECTORY_SEPARATOR . 'Output' . DIRECTORY_SEPARATOR;
        return $dir;

    }
}