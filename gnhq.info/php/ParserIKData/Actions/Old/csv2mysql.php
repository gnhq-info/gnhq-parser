<?php
include_once 'include.php';

$electionCode = '412';
/**
* @var ParserIKData_Warehouse_Interface
*/
$warehouse = ParserIKData_ServiceLocator::getInstance()->getWarehouse();
$warehouseCsv = new ParserIKData_Warehouse_Csv();
$warehouseCsv
    //->loadElectionResults($electionCode, 'OF')
    ->loadElectionWatches($electionCode, 'GN');


$warehouse
    //->saveElectionResults($electionCode, 'OF')
    ->saveElectionWatches($electionCode, 'GN');