<?php
ParserIKData_ServiceLocator::getInstance()
    ->setService('Gateway_Violation', new ParserIKData_Gateway_Violation_Mosobl())
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Mosobl())
    ->setService('Gateway_Protocol', new ParserIKData_Gateway_Protocol_Mosobl())
    ->setService('Gateway_ProtocolOf', new ParserIKData_Gateway_Protocol_MosoblOf())
;