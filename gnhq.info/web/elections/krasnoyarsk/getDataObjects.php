<?php
ParserIKData_ServiceLocator::getInstance()
    ->setService('Gateway_Violation', new ParserIKData_Gateway_Violation_Krasnoyarsk())
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Krasnoyarsk())
    ->setService('Gateway_Protocol', new ParserIKData_Gateway_Protocol_Krasnoyarsk())
    ->setService('Gateway_ProtocolOf', new ParserIKData_Gateway_Protocol_KrasnoyarskOf())
;