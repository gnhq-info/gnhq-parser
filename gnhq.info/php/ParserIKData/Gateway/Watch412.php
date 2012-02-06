<?php
class ParserIKData_Gateway_Watch412 extends ParserIKData_Gateway_Abstract
{
    private $_table = 'watch_412';

    public function getTotalUiksCount()
    {
        $result = $this->_getDriver()->query('SELECT COUNT(*) FROM '.$this->_table);
        while ($res = mysql_fetch_array($result)) {
            return ($res[0]);
        }
    }
}