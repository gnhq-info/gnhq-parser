<?php
/**
* @author admin
*/
class ParserIKData_Gateway_Watch_Mosmer extends ParserIKData_Gateway_Watch
{
    protected $_table = 'gnhq_mosmer.watch';

    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol_Mosmer();
    }
}