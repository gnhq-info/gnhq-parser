<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_Krasnoyarsk extends ParserIKData_Gateway_Protocol
{
    protected $_table = 'gnhq_krasnoyarsk.result';
    protected $_reservTable = 'gnhq_krasnoyarsk.result_copy';
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
        return '(Line10 = Line13 + Line14 + Line15 + Line16 + Line17 + Line18 + Line19)';
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
    * @return ParserIKData_Gateway_UIK
    */
    protected function _getUikGeneralGateway()
    {
        return new ParserIKData_Gateway_Uik_Krasnoyarsk();
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