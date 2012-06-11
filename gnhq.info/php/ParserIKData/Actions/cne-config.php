<?php
/* create config * /
$confFileName = rtrim(INI_CONFIG_PATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $electionName . '.ini';
if (!file_exists($confFileName)) {
if ($f = fopen($confFileName, 'a+')) {
fclose($f);
} else {
die('cant write to '.$confFileName);
}
$data = '';
foreach ($config as $k => $v) {
$data .= $k . '="' . $v . '"' . PHP_EOL;
}
$data .= 'site=""

regionIndicator="сайт избирательной комиссии субъекта Российской Федерации"
mainIndicator="Главная страница"


lowerIKIndicator="Нижестоящие избирательные комиссии"
lowerIKSelectIndicator="select name=\"gs\""

totalTableIndicator="Число избирателей, внесенных в список избирателей"
resultTableIndicator="cellpadding"
useCache=1';
$f = fopen($confFileName, 'a+');
fwrite($f, $data);
fclose($f);
unset($data);
} */