<?php
define('PROJECT_STARTED', 1);
include 'webinclude.php';

$view->diagRows = array(
array('key' => 'VZ',  'hdr' => 'В. Жириновский',  'title' => 'В. Жириновский', 'color' => 'yellow'),
array('key' => 'GZ',  'hdr' => 'Г. Зюганов',      'title' => 'Г. Зюганов', 'color' => 'orange'),
array('key' => 'SM',  'hdr' => 'С. Миронов',      'title' => 'С. Миронов', 'color' => '#ff77ff'),
array('key' => 'MP',  'hdr' => 'М. Прохоров',     'title' => 'М. Прохоров', 'color' => 'red'),
array('key' => 'VP',  'hdr' => 'В. Путин',        'title' => 'В. Путин', 'color' => '#66ee00'),
array('key' => 'AT', 'hdr' => 'Явка',             'title' => 'Явка', 'color' => 'magenta'),
array('key' => 'SP', 'hdr' => 'Недействительные', 'title' => 'Недействительные', 'color' => 'grey'),
);


if ($_SERVER['HTTP_HOST'] == 'gnhq.localhost') {
    if (!empty($_GET['showResult'])) {
        $view->onlyResult = true;
    } else {
        $view->onlyResult = false;
    }
    include 'tpl/violfull.phtml';
} else {
    $view->onlyResult = false;
    include 'tpl/viol.phtml';
}