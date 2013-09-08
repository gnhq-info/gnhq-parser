<?php
class ParserIKData_Gateway_Violation_Mosmer extends ParserIKData_Gateway_Violation
{
    protected $_table = 'gnhq_mosmer.violation';
    protected $_reservTable = 'gnhq_mosmer.violation_copy';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 60;
    }
}