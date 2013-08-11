<?php
ParserIKData_ServiceLocator::getInstance()
    ->setService('Gateway_Violation', new ParserIKData_Gateway_Violation_Omsk())
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Omsk())
    ->setService('Gateway_Protocol', new ParserIKData_Gateway_Protocol_Omsk())
    ->setService('Gateway_ProtocolOf', new ParserIKData_Gateway_Protocol_OmskOf())
;