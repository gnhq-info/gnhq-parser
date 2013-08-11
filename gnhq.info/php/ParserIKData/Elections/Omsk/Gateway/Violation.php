<?php
class ParserIKData_Gateway_Violation_Omsk extends ParserIKData_Gateway_Violation
{
    protected $_table = 'gnhq_omsk.violation';
    protected $_reservTable = 'gnhq_omsk.violation_copy';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 1;
    }
}