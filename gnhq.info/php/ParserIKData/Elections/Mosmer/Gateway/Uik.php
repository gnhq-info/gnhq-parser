<?php
class ParserIKData_Gateway_Uik_Mosmer extends ParserIKData_Gateway_UIKRussia
{
    protected $_table = 'gnhq_mosmer.uik';

    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol_Mosmer();
    }

    public function getOne()
    {
        return $this->_loadFromTable($this->_table, $this->_modelClass, 'FullName = 771562');
    }
}