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
        if (false === ($data = $this->_loadFromCache(__CLASS__, __FUNCTION__, $args)) ) {
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

            $this->_saveToCache(__CLASS__, __FUNCTION__, $args, $data);
        }
        return $data;
    }
}