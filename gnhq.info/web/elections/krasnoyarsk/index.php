<?php
$view = new stdClass();
$view->diagRows =array (
  0 =>
  array (
    'pLine' => 13,
    'key' => 'EA',
    'name' => 'Эдхам Акбулатов',
    'color' => 'yellow',
    'hdr' => 'Эдхам Акбулатов',
    'title' => 'Эдхам Акбулатов',
  ),
  1 =>
  array (
    'pLine' => 14,
    'key' => 'MI',
    'name' => 'Максим Иваныч',
    'color' => 'orange',
    'hdr' => 'Максим Иваныч',
    'title' => 'Максим Иваныч',
  ),
  2 =>
  array (
    'pLine' => 15,
    'key' => 'NK',
    'name' => 'Нина Коврова',
    'color' => '#ff77ff',
    'hdr' => 'Нина Коврова',
    'title' => 'Нина Коврова',
  ),
  3 =>
  array (
    'pLine' => 16,
    'key' => 'AK',
    'name' => 'Александр Коропачинский',
    'color' => '#77ffff',
    'hdr' => 'Александр Коропачинский',
    'title' => 'Александр Коропачинский',
  ),
  4 =>
  array (
    'pLine' => 17,
    'key' => 'AM',
    'name' => 'Алексей Мещеряков',
    'color' => '#ffff77',
    'hdr' => 'Алексей Мещеряков',
    'title' => 'Алексей Мещеряков',
  ),
  5 =>
  array (
    'pLine' => 18,
    'key' => 'MO',
    'name' => 'Михаил Осколков',
    'color' => 'red',
    'hdr' => 'Михаил Осколков',
    'title' => 'Михаил Осколков',
  ),
  6 =>
  array (
    'pLine' => 19,
    'key' => 'AP',
    'name' => 'Алексей Подкорытов',
    'color' => 'blue',
    'hdr' => 'Алексей Подкорытов',
    'title' => 'Алексей Подкорытов',
  ),
);

            	$view->electionsName = 'Выборы мэра Красноярска.';
				$folder = 'krasnoyarsk';
				$defaultRegion = '24';

            $JS_SCRIPT_VERSION = 1;
			$CSS_VERSION = 1;
			require(rtrim(TPL_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR. 'full.phtml');