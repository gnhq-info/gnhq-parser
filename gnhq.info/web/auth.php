<?php
if (!defined('PROJECT_STARTED')) {
    die();
}

$db = ParserIKData_ServiceLocator::getInstance()->getMySql();

$session = $_SESSION['session_id'];
if (!$session) {
    header('Location: https://gnhq.info/login.cgi?rp=https://gnhq.info/stat/web/index.php');
    exit;
}
