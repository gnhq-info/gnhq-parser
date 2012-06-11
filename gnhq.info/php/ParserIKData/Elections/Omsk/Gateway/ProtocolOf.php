<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_OmskOf extends ParserIKData_Gateway_Protocol_Omsk
{
    protected $_table = 'omsk_result_of';
    protected $_reservTable = 'omsk_result_of_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol_Omsk';
}