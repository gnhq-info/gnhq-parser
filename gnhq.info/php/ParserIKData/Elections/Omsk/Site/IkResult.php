<?php
class ParserIKData_Site_CikUIK_Omsk extends ParserIKData_Site_CikUIK
{
    protected function _createBlankProtocol()
    {
        return ParserIKData_Model_Protocol_Omsk::create();
    }
}