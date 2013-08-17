<?php
ParserIKData_ServiceLocator::getInstance()
    ->setService('Gateway_Violation', new ParserIKData_Gateway_Violation_Mosmer())
    ->setService('Gateway_Uik', new ParserIKData_Gateway_Uik_Mosmer())
    ->setService('Gateway_Protocol', new ParserIKData_Gateway_Protocol_Mosmer())
    ->setService('Gateway_ProtocolOf', new ParserIKData_Gateway_Protocol_MosmerOf())
;