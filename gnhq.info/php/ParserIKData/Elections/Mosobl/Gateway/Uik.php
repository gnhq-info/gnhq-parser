<?php
class ParserIKData_Gateway_Uik_Mosobl extends ParserIKData_Gateway_UIKRussia
{
    protected $_table = 'gnhq_mosobl.uik';

    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol_Mosobl();
    }
}