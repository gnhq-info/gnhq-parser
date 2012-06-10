<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_Krasnoyarsk extends ParserIKData_Gateway_Protocol
{
    protected $_table = 'krasnoyarsk_result';
    protected $_reservTable = 'krasnoyarsk_result_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol_Krasnoyarsk';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return null;
    }

    /**
     * @return string
     */
    protected function _getCondControlRel()
    {
        return '(Line10 = Line19 + Line20 + Line21 + Line22 + Line23 + Line24 + Line25)';
    }


    /**
     * @return int
     */
    protected function _getLineAmount()
    {
        return ParserIKData_Model_Protocol_Krasnoyarsk::getLineAmount();
    }


    /**
     * @return int
     */
    protected function _getAllowableDiscrepancy()
    {
        return ParserIKData_Model_Protocol_Krasnoyarsk::getAllowableDiscrepancy();
    }


    /**
     * @return int[]
     */
    protected function _getIndicesForCompare()
    {
        return ParserIKData_Model_Protocol_Krasnoyarsk::getIndicesForCompare();
    }

    /**
     * @return ParserIKData_Gateway_Watch_Krasnoyarsk
     */
    protected function _getWatchGateway()
    {
        return new ParserIKData_Gateway_Watch_Krasnoyarsk();
    }
}