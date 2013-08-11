<?php
class ParserIKData_XMLProcessor_Protocol_Omsk extends ParserIKData_XMLProcessor_Protocol
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_XMLProcessor_Protocol::_getLineAmount()
     */
    protected function _getLineAmount()
    {
        return ParserIKData_Model_Protocol_Omsk::getLineAmount();
    }

    /**
     * @return ParserIKData_Gateway_Protocol
     */
    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol_Omsk();
    }

    /**
     * @return int[]
     */
    protected function _getMandatoryIndices()
    {
		return array(9, 10, 12, 13, 14, 15, 16, 17, 18);
	}

    /**
     * (non-PHPdoc)
     * @see ParserIKData_XMLProcessor_Protocol::_getNewProtocol()
     */
    protected function _getNewProtocol()
    {
        return ParserIKData_Model_Protocol_Omsk::create();
    }
}