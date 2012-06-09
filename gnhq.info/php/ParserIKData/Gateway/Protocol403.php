<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol403 extends ParserIKData_Gateway_Protocol
{
    protected $_table = 'result_403';
    protected $_reservTable = 'result_403_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol403';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 3600;
    }

    /**
     * @return string
     */
    protected function _getCondControlRel()
    {
        return '(Line10 = Line19 + Line20 + Line21 + Line22 + Line23)';
    }


    /**
     * @return int
     */
    protected function _getLineAmount()
    {
        return ParserIKData_Model_Protocol403::getLineAmount();
    }


    /**
     * @return int
     */
    protected function _getAllowableDiscrepancy()
    {
        return ParserIKData_Model_Protocol403::getAllowableDiscrepancy();
    }


    /**
     * @return int[]
     */
    protected function _getIndicesForCompare()
    {
        return ParserIKData_Model_Protocol403::getIndicesForCompare();
    }

    /**
     * @return ParserIKData_Gateway_Watch403
     */
    protected function _getWatchGateway()
    {
        return new ParserIKData_Gateway_Watch403();
    }
}