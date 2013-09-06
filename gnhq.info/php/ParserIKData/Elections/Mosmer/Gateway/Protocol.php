<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_Mosmer extends ParserIKData_Gateway_Protocol
{
    protected $_table = 'gnhq_mosmer.result';
    protected $_reservTable = 'gnhq_mosmer.result_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol_Mosmer';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 120;
    }

    /**
     * @return string
     */
    protected function _getCondControlRel()
    {
		return '(Line10 = Line12 + Line13 + Line14 + Line15 + Line16 + Line17 )';
	}


    /**
     * @return int
     */
    protected function _getLineAmount()
    {
        return ParserIKData_Model_Protocol_Mosmer::getLineAmount();
    }


    /**
     * @return int
     */
    protected function _getAllowableDiscrepancy()
    {
        return ParserIKData_Model_Protocol_Mosmer::getAllowableDiscrepancy();
    }


    /**
    * @return ParserIKData_Gateway_UIK
    */
    protected function _getUikGeneralGateway()
    {
        return new ParserIKData_Gateway_Uik_Mosmer();
    }

    /**
     * @return int[]
     */
    protected function _getIndicesForCompare()
    {
        return ParserIKData_Model_Protocol_Mosmer::getIndicesForCompare();
    }

    /**
     * @return ParserIKData_Gateway_Watch_Mosmer
     */
    protected function _getWatchGateway()
    {
        return new ParserIKData_Gateway_Watch_Mosmer();
    }
}