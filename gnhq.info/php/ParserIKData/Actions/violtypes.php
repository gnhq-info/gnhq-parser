<?php
include_once 'include.php';

switch ($argv[1])
{
    case PROJECT_GN:

        $gateway = new ParserIKData_Gateway_ViolationType();
        $gateway->removeForProject(PROJECT_GN);
        $data = file_get_contents(DATA_PATH . 'gn-violtype.txt');
        $lines = explode(PHP_EOL, $data);
        foreach ($lines as $line) {
            list($gnType, $name) = explode(";", $line, 2);
            $name = mb_convert_encoding($name, 'utf-8', 'cp1251');
            $vType = ParserIKData_Model_ViolationType::fromArray(array(trim($gnType), trim($gnType), PROJECT_GN, trim($name)));
            $gateway->save($vType);
        }
        break;


    default:
        break;
}

function _normalize($str)
{
    return trim(mb_strtolower($str, mb_detect_encoding($str)));
}