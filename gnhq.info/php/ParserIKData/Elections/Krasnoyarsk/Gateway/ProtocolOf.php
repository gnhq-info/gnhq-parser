<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_KrasnoyarskOf extends ParserIKData_Gateway_Protocol_Krasnoyarsk
{
    protected $_table = 'krasnoyarsk_result_of';
    protected $_reservTable = 'krasnoyarsk_result_of_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol_Krasnoyarsk';
}