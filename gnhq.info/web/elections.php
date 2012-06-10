<?php
$electionsMap = array(
	'krasnoyarsk' => 'krasnoyarsk'
);


$req = isset($_GET['el']) ? $_GET['el'] : '';
if (!$req) {
    die('bad request');
}
if (empty($electionsMap[$req])) {
    die('bad request');
}

define('PROJECT_STARTED', 1);
require_once 'webinclude.php';

require_once 'elections/'.$electionsMap[$req].'/index.php';
