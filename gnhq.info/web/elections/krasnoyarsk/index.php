<?php
$view->diagRows = array(
    array('key' => 'EA', 'hdr' => 'Эдхам Акбулатов',         'title' => 'Эдхам Акбулатов',         'color' => 'yellow'),
    array('key' => 'MI', 'hdr' => 'Максим Иваныч',           'title' => 'Максим Иваныч',           'color' => 'orange'),
    array('key' => 'NK', 'hdr' => 'Нина Коврова',            'title' => 'Нина Коврова',            'color' => '#ff77ff'),
    array('key' => 'AK', 'hdr' => 'Александр Коропачинский', 'title' => 'Александр Коропачинский', 'color' => '#77ffff'),
    array('key' => 'AM', 'hdr' => 'Алексей Мещеряков',       'title' => 'Алексей Мещеряков',       'color' => '#ffff77'),
    array('key' => 'MO', 'hdr' => 'Михаил Осколков',         'title' => 'Михаил Осколков',         'color' => 'red'),
    array('key' => 'AP', 'hdr' => 'Алексей Подкорытов',      'title' => 'Алексей Подкорытов',      'color' => '#66ee00'),
    array('key' => 'AT', 'hdr' => 'Явка',             		 'title' => 'Явка',                    'color' => 'magenta'),
    array('key' => 'SP', 'hdr' => 'Недействительные',        'title' => 'Недействительные',        'color' => 'grey'),
);

$view->electionsName = 'Выборы мэра Красноярска. 10.06.2012';
$folder = 'krasnoyarsk';
$defaultRegion = '24';
$JS_SCRIPT_VERSION = 1;
$CSS_VERSION = 1;

require(rtrim(TPL_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR. 'full.phtml');