<?php
class ParserIKData_Gateway_Violation_Omsk extends ParserIKData_Gateway_Violation
{
    protected $_table = 'omsk_violation';
    protected $_reservTable = 'omsk_violation_copy';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 1;
    }
}