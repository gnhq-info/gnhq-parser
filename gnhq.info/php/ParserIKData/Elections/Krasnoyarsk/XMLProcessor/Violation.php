<?php
class ParserIKData_XMLProcessor_Violation_Krasnoyarsk extends ParserIKData_XMLProcessor_Violation
{
    protected function _getViolationGateway()
    {
        return new ParserIKData_Gateway_Violation_Krasnoyarsk();
    }
}