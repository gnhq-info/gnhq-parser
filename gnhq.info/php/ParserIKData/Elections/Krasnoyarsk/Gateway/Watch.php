<?php
/**
* @author admin
*/
class ParserIKData_Gateway_Watch_Krasnoyarsk extends ParserIKData_Gateway_Watch
{
    protected $_table = 'gnhq_krasnoyarsk.watch';

    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol_Krasnoyarsk();
    }
}