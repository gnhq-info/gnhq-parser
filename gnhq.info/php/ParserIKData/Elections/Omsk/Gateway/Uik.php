<?php
class ParserIKData_Gateway_Uik_Omsk extends ParserIKData_Gateway_UIKRussia
{
    protected $_table = 'gnhq_omsk.uik';

    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol_Omsk();
    }
}