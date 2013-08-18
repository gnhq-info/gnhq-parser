<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_Mosobl extends ParserIKData_Gateway_Protocol
{
    protected $_table = 'gnhq_mosobl.result';
    protected $_reservTable = 'gnhq_mosobl.result_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol_Mosobl';

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
		return '(Line10 = Line12 + Line13 + Line14 + Line15 + Line16 + Line17 )';
	}


    /**
     * @return int
     */
    protected function _getLineAmount()
    {
        return ParserIKData_Model_Protocol_Mosobl::getLineAmount();
    }


    /**
     * @return int
     */
    protected function _getAllowableDiscrepancy()
    {
        return ParserIKData_Model_Protocol_Mosobl::getAllowableDiscrepancy();
    }


    /**
    * @return ParserIKData_Gateway_UIK
    */
    protected function _getUikGeneralGateway()
    {
        return new ParserIKData_Gateway_Uik_Mosobl();
    }

    /**
     * @return int[]
     */
    protected function _getIndicesForCompare()
    {
        return ParserIKData_Model_Protocol_Mosobl::getIndicesForCompare();
    }

    /**
     * @return ParserIKData_Gateway_Watch_Mosobl
     */
    protected function _getWatchGateway()
    {
        return new ParserIKData_Gateway_Watch_Mosobl();
    }
}