<?php
/* create tables */
$sql_template = file_get_contents(APPLICATION_DIR_ROOT . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'elect.sql');
$commands = explode(';', $sql_template);
$lineTemplate = '`Line1` int(11) NOT NULL';
$db = ParserIKData_ServiceLocator::getInstance()->getMySql();
foreach ($commands as $command) {
    if (!trim($command, "\r\n")) {
        continue;
    }
    $command = str_replace('election-preffix_', $electionName . '_', $command);

    if (strpos($command, $lineTemplate) > 0) {
        $repl = array();
        for ($j = 1; $j < $maxLines; $j++) {
            $repl[] = str_replace('Line1', 'Line' . $j, $lineTemplate);
        }
        $repl = implode(', ', $repl);
        $command = str_replace($lineTemplate, $repl, $command);
    }

    $db->query($command);
}