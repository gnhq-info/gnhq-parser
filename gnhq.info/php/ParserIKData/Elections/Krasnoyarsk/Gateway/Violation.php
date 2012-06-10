<?php
class ParserIKData_Gateway_Violation_Krasnoyarsk extends ParserIKData_Gateway_Violation
{
    protected $_table = 'krasnoyarsk_violation';
    protected $_reservTable = 'krasnoyarsk_violation_copy';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 1;
    }
}