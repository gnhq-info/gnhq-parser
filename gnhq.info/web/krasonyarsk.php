<?php
define('PROJECT_STARTED', 1);
include 'webinclude.php';

$folder = 'krasnoyarsk';


$view->diagRows = array(
    array('key' => 'VZ',  'hdr' => 'В. Жириновский',  'title' => 'В. Жириновский', 'color' => 'yellow'),
    array('key' => 'GZ',  'hdr' => 'Г. Зюганов',      'title' => 'Г. Зюганов', 'color' => 'orange'),
    array('key' => 'SM',  'hdr' => 'С. Миронов',      'title' => 'С. Миронов', 'color' => '#ff77ff'),
    array('key' => 'MP',  'hdr' => 'М. Прохоров',     'title' => 'М. Прохоров', 'color' => 'red'),
    array('key' => 'VP',  'hdr' => 'В. Путин',        'title' => 'В. Путин', 'color' => '#66ee00'),
    array('key' => 'AT', 'hdr' => 'Явка',             'title' => 'Явка', 'color' => 'magenta'),
    array('key' => 'SP', 'hdr' => 'Недействительные', 'title' => 'Недействительные', 'color' => 'grey'),
);


include 'tpl/'.$folder.'/full.phtml';
