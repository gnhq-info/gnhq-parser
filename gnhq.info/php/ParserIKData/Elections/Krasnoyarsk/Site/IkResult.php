<?php
class ParserIKData_Site_CikUIK_Krasnoyarsk extends ParserIKData_Site_CikUIK
{
    protected function _createBlankProtocol()
    {
        return ParserIKData_Model_Protocol_Krasnoyarsk::create();
    }
}