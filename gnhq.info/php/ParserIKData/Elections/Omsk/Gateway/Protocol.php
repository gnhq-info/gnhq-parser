<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_Omsk extends ParserIKData_Gateway_Protocol
{
    protected $_table = 'gnhq_omsk.result';
    protected $_reservTable = 'gnhq_omsk.result_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol_Omsk';

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
		return '(Line10 = Line13 + Line14 + Line15 + Line16 + Line17 + Line18 + Line19 )';
	}


    /**
     * @return int
     */
    protected function _getLineAmount()
    {
        return ParserIKData_Model_Protocol_Omsk::getLineAmount();
    }


    /**
     * @return int
     */
    protected function _getAllowableDiscrepancy()
    {
        return ParserIKData_Model_Protocol_Omsk::getAllowableDiscrepancy();
    }


    /**
    * @return ParserIKData_Gateway_UIK
    */
    protected function _getUikGeneralGateway()
    {
        return new ParserIKData_Gateway_Uik_Omsk();
    }

    /**
     * @return int[]
     */
    protected function _getIndicesForCompare()
    {
        return ParserIKData_Model_Protocol_Omsk::getIndicesForCompare();
    }

    /**
     * @return ParserIKData_Gateway_Watch_Omsk
     */
    protected function _getWatchGateway()
    {
        return new ParserIKData_Gateway_Watch_Omsk();
    }
}