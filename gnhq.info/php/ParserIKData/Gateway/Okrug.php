<?php
class ParserIKData_Gateway_Okrug extends ParserIKData_Gateway_Abstract
{
    private $_view_uik_okrug = 'uik_okrug';

    /**
     * @return array
     */
    public function getDiscrepancyCount()
    {
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
        return $data;
    }
}