<?php
class ParserIKData_Gateway_Okrug extends ParserIKData_Gateway_Abstract
{
    private $_view_uik_okrug = 'uik_okrug';

    /**
     * @return array
     */
    public function getDiscrepancyCount()
    {
        $query =  'SELECT okrug, count(*) as DiscrCount FROM '.$this->_view_uik_okrug . '
        	WHERE uik IN ('.$this->_getProtocolGateway()->getCondDiscrepancy(). ') GROUP BY okrug';
        $result = $this->_getDriver()->query($query);

        $data = array();
        while ($arr = $this->_getDriver()->fetchResultToArray($result)) {
            $data[$arr[0]] = $arr[1];
        }
        return $data;
    }
}