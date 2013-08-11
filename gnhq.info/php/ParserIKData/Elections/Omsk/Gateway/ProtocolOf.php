<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_OmskOf extends ParserIKData_Gateway_Protocol_Omsk
{
    protected $_table = 'gnhq_omsk.result_of';
    protected $_reservTable = 'gnhq_omsk.result_of_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol_Omsk';
}