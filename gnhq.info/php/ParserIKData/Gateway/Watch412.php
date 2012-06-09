<?php
class ParserIKData_Gateway_Watch412 extends ParserIKData_Gateway_Abstract
{
    private $_table = 'watch_412';

    public function getCount($watchType, $okrugAbbr = null, $withDiscrepancy = false, $withProtocol = false)
    {
        $args = func_get_args();
        if (false === ($count = $this->_loadFromCache(get_called_class(), __FUNCTION__, $args)) ) {
            $conds = array();
            $conds[] = 'WatchType = "'.$this->_escapeString($watchType).'"';
            if ($okrugAbbr) {
                $conds[] = 'uik in (' . $this->_getUikGateway()->getCondOkrug($okrugAbbr) . ')';
            }
            if ($withDiscrepancy) {
                $conds[] = 'uik in (' .$this->_getProtocolGateway()->getCondDiscrepancy($watchType, null, null) . ')';
            }
            if ($withProtocol) {
                $conds[] = 'uik in (' . $this->_getProtocolGateway()->getCondResultType($watchType) . ')';
            }
            $cond = '(' . implode(') AND (', $conds) . ')';
            $result = $this->_getDriver()->query('SELECT COUNT(*) FROM '. $this->_table . ' WHERE ' . $cond);
            while ($res = $this->_fetchResultToArray($result)) {
                $count = $res[0];
            }

            $this->_saveToCache(get_called_class(), __METHOD__, $args, $count);
        }

        return $count;
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