<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_KrasnoyarskOf extends ParserIKData_Gateway_Protocol_Krasnoyarsk
{
    protected $_table = 'gnhq_krasnoyarsk.result_of';
    protected $_reservTable = 'gnhq_krasnoyarsk.result_of_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol_Krasnoyarsk';
}