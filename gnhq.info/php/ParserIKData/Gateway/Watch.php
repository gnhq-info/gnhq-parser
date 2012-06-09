<?php
abstract class ParserIKData_Gateway_Watch extends ParserIKData_Gateway_Abstract
{
    protected $_table = '';

    /**
    * @return ParserIKData_Gateway_Protocol
    */
    protected function _getProtocolGateway() {
        throw new Exception('must be implemented');
    }

    protected function _getCacheLifetime()
    {
        return null;
    }

    public function getCount($watchType, $okrugAbbr = null, $withDiscrepancy = false, $withProtocol = false)
    {
        $args = func_get_args();
        if (false === ($count = $this->_loadFromCache(get_called_class(), __FUNCTION__, $args)) ) {
            $conds = array();
            $conds[] = 'WatchType '.$this->_getWatchTypeString($watchType);
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
        return 'SELECT uik FROM ' . $this->_table . ' WHERE WatchType '.$this->_getWatchTypeString($watchType);
    }

    /**
    * @param string $watchType
    * @return string
    */
    public function getCondClear($watchType)
    {
        return 'SELECT uik FROM ' . $this->_table . ' WHERE WatchType '.$this->_getWatchTypeString($watchType).' AND code = 1';
    }

    private function _getWatchTypeString($watchType)
    {
        $parts = array();
        foreach ($watchType as $wType) {
            $parts[] = $this->_escapeString($wType);
        }
        return ' IN ("' . implode ('", "', $parts) . '")';
    }
}