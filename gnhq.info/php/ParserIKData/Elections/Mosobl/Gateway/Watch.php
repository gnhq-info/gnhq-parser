<?php
/**
* @author admin
*/
class ParserIKData_Gateway_Watch_Mosobl extends ParserIKData_Gateway_Watch
{
    protected $_table = 'gnhq_mosobl.watch';

    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol_Mosobl();
    }
}