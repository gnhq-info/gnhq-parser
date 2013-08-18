<?php
class ParserIKData_Site_CikUIK_Mosobl extends ParserIKData_Site_CikUIK
{
    protected function _createBlankProtocol()
    {
        return ParserIKData_Model_Protocol_Mosobl::create();
    }
}