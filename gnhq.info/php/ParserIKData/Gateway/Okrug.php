<?php
class ParserIKData_Gateway_Okrug extends ParserIKData_Gateway_Abstract
{
    private $_view_uik_okrug = 'uik_okrug';

    /**
     * @return array
     */
    public function getDiscrepancyCount()
    {
        $args = func_get_args();
        if (!$this->useCache() || false === ($data = $this->_getCache()->read($this->_buildCacheKey(__METHOD__, $args))) ) {
            $result = $this->_getDriver()->selectAssoc(
        		'okrug, count(*) as DiscrCount',
                $this->_view_uik_okrug,
            	'uik IN ('.$this->_getProtocolGateway()->getCondDiscrepancy(). ')',
                null,
                null,
        		'okrug'
                );
            foreach ($result as $i => $row) {
                $data[$row['okrug']] = $row['DiscrCount'];
            }

            if ($this->useCache()) {
                $this->_getCache()->save($this->_buildCacheKey(__METHOD__, $args), $data);
            }
        }
        return $data;
    }
}