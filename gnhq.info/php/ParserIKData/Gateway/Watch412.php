<?php
class ParserIKData_Gateway_Watch412 extends ParserIKData_Gateway_Abstract
{
    private $_table = 'watch_412';

    public function getTotalUiksCount()
    {
        $result = $this->_getDriver()->query('SELECT COUNT(*) FROM '.$this->_table);
        while ($res = $this->_fetchResultToArray($result)) {
            return ($res[0]);
        }
    }

    /**
     * @param string $watchType
     * @return string
     */
    public function getCondIn($watchType)
    {
        return 'SELECT uik FROM ' . $this->_table . ' WHERE WatchType = "'.$this->_escapeString($watchType).'"';
    }

    /**
    * @param string $watchType
    * @return string
    */
    public function getCondClear($watchType)
    {
        return 'SELECT uik FROM ' . $this->_table . ' WHERE WatchType = "'.$this->_escapeString($watchType).'" AND code = 1';
    }
}