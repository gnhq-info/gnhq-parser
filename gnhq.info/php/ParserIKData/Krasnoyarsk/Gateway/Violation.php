<?php
class ParserIKData_Gateway_Violation_Krasnoyarsk extends ParserIKData_Gateway_Violation
{
    protected $_table = 'violation_krasnoyarsk';
    protected $_reservTable = 'violation_krasnoyarsk_copy';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return null;
    }
}