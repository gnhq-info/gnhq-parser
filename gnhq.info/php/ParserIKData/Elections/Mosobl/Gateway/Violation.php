<?php
class ParserIKData_Gateway_Violation_Mosobl extends ParserIKData_Gateway_Violation
{
    protected $_table = 'gnhq_mosobl.violation';
    protected $_reservTable = 'gnhq_mosobl.violation_copy';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 1;
    }
}