<?php
class ParserIKData_XMLProcessor_Protocol403 extends ParserIKData_XMLProcessor_Protocol
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_XMLProcessor_Protocol::_getLineAmount()
     */
    protected function _getLineAmount()
    {
        return ParserIKData_Model_Protocol403::getLineAmount();
    }

    /**
     * @return ParserIKData_Gateway_Protocol
     */
    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol403();
    }

    /**
     * @return int[]
     */
    protected function _getMandatoryIndices()
    {
        return array(9, 10, 19, 20, 21, 22, 23);
    }

    /**
     * (non-PHPdoc)
     * @see ParserIKData_XMLProcessor_Protocol::_getNewProtocol()
     */
    protected function _getNewProtocol()
    {
        return ParserIKData_Model_Protocol403::create();
    }
}