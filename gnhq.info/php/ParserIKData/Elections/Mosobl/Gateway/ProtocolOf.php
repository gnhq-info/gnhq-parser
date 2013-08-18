<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_Protocol_MosoblOf extends ParserIKData_Gateway_Protocol_Mosobl
{
    protected $_table = 'gnhq_mosobl.result_of';
    protected $_reservTable = 'gnhq_mosobl.result_of_copy';
    protected $_modelClass = 'ParserIKData_Model_Protocol_Mosobl';
}