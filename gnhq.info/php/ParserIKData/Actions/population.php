<?php
include_once 'include.php';

$gateway = new ParserIKData_Gateway_Region();
$regions = $gateway->getAll();
$regionsByNums = array();
foreach ($regions as $region) {
    $regionsByNums[$region->getRegionNum()] = $region;
}

$pfile = fopen(DATA_PATH . 'population.csv', 'rb');
if (!$pfile) {
    die('cant open file');
}

while ($data = fgetcsv($pfile, 0, ';', '"')) {
    if (!empty($data[0]) && !empty($data[1]) && !empty($regionsByNums[$data[0]])) {
        $regionsByNums[$data[0]]->setPopulation($data[1]);
        $gateway->update($regionsByNums[$data[0]]);
    }
}

print 'population loaded';

