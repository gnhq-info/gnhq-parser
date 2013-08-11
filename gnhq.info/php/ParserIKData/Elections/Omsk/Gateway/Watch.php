<?php
/**
* @author admin
*/
class ParserIKData_Gateway_Watch_Omsk extends ParserIKData_Gateway_Watch
{
    protected $_table = 'gnhq_omsk.watch';

    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol_Omsk();
    }
}