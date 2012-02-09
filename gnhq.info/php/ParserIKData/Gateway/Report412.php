<?php
class ParserIKData_Gateway_Report412 extends ParserIKData_Gateway_Abstract
{
    private $_table = 'report_412';

    /**
     * @param int $uik
     * @return ParserIKData_Model_Report412|null
     */
    public function getForUik($uik)
    {
        $conds = array();
        $conds[] =  'uik = ' . intval($uik) . '';
        $cond = '( ' . implode(' ) AND (', $conds) . ' )';
        $result = $this->_getDriver()->select('*', $this->_table, $cond, null, null);
        while ( ($data = $this->_fetchResultToArray($result)) !== false) {
            return ParserIKData_Model_Report412::fromArray($data);
        }
        return null;
    }
}