<?php
$view = new stdClass();
$view->diagRows =array (
  0 =>
  array (
    'pLine' => 12,
    'key' => 'AI',
    'name' => 'Антропов Игорь',
    'color' => 'yellow',
    'hdr' => 'Антропов Игорь',
    'title' => 'Антропов Игорь',
  ),
  1 =>
  array (
    'pLine' => 13,
    'key' => 'DV',
    'name' => 'Двораковский Вячеслав',
    'color' => 'orange',
    'hdr' => 'Двораковский Вячеслав',
    'title' => 'Двораковский Вячеслав',
  ),
  2 =>
  array (
    'pLine' => 14,
    'key' => 'GV',
    'name' => 'Жарков Виктор',
    'color' => '#ff77ff',
    'hdr' => 'Жарков Виктор',
    'title' => 'Жарков Виктор',
  ),
  3 =>
  array (
    'pLine' => 15,
    'key' => 'ZY',
    'name' => 'Зелинский Ян',
    'color' => '#77ffff',
    'hdr' => 'Зелинский Ян',
    'title' => 'Зелинский Ян',
  ),
  4 =>
  array (
    'pLine' => 16,
    'key' => 'KA',
    'name' => 'Коротков Александр',
    'color' => '#ffff77',
    'hdr' => 'Коротков Александр',
    'title' => 'Коротков Александр',
  ),
  5 =>
  array (
    'pLine' => 17,
    'key' => 'MS',
    'name' => 'Масленков Сергей',
    'color' => 'red',
    'hdr' => 'Масленков Сергей',
    'title' => 'Масленков Сергей',
  ),
  6 =>
  array (
    'pLine' => 18,
    'key' => 'OI',
    'name' => 'Оверина Ирина',
    'color' => 'blue',
    'hdr' => 'Оверина Ирина',
    'title' => 'Оверина Ирина',
  ),
);

            	$view->electionsName = 'Выборы мэра Омска.';
				$folder = 'omsk';
				$defaultRegion = '55';

            $JS_SCRIPT_VERSION = 1;
			$CSS_VERSION = 1;
			require(rtrim(TPL_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR. 'full.phtml');