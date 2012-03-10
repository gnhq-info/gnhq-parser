<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol403Offile extends ParserIKData_Gateway_Protocol403
{
    protected $_table = 'result_403_offile';

    /**
    * @return null|int
    */
    protected function _getCacheLifetime()
    {
        return 86400;
    }
}