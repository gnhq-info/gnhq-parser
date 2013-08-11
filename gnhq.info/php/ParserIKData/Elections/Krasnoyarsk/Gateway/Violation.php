<?php
class ParserIKData_Gateway_Violation_Krasnoyarsk extends ParserIKData_Gateway_Violation
{
    protected $_table = 'gnhq_krasnoyarsk.violation';
    protected $_reservTable = 'gnhq_krasnoyarsk.violation_copy';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 1;
    }
}