<?php
/**
* @author admin
*/
class ParserIKData_Gateway_Watch403 extends ParserIKData_Gateway_Watch
{
    protected $_table = 'watch_403';

    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol403();
    }
}