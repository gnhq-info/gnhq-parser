<?php
$view = new stdClass();
$view->diagRows =array (
  0 =>
  array (
    'pLine' => 12,
    'key' => 'DM',
    'name' => 'Дегтярев Михаил',
    'color' => 'yellow',
    'hdr' => 'Дегтярев Михаил',
    'title' => 'Дегтярев Михаил',
  ),
  1 =>
  array (
    'pLine' => 13,
    'key' => 'LN',
    'name' => 'Левичев Николай',
    'color' => 'orange',
    'hdr' => 'Левичев Николай',
    'title' => 'Левичев Николай',
  ),
  2 =>
  array (
    'pLine' => 14,
    'key' => 'MI',
    'name' => 'Мельников Иван',
    'color' => '#ff77ff',
    'hdr' => 'Мельников Иван',
    'title' => 'Мельников Иван',
  ),
  3 =>
  array (
    'pLine' => 15,
    'key' => 'MS',
    'name' => 'Митрохин Сергей',
    'color' => '#77ffff',
    'hdr' => 'Митрохин Сергей',
    'title' => 'Митрохин Сергей',
  ),
  4 =>
  array (
    'pLine' => 16,
    'key' => 'NA',
    'name' => 'Навальный Алексей',
    'color' => '#ffff77',
    'hdr' => 'Навальный Алексей',
    'title' => 'Навальный Алексей',
  ),
  5 =>
  array (
    'pLine' => 17,
    'key' => 'SS',
    'name' => 'Собянин Сергей',
    'color' => 'red',
    'hdr' => 'Собянин Сергей',
    'title' => 'Собянин Сергей',
  ),
);

            	$view->electionsName = 'Выборы мэра Москвы. 08.09.2013';
				$folder = 'mosmer';
				$defaultRegion = '77';

            $JS_SCRIPT_VERSION = 1;
			$CSS_VERSION = 1;
			require(rtrim(TPL_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR. 'full.phtml');