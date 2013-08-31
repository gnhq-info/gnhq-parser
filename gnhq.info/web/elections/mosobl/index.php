<?php
$view = new stdClass();
$view->diagRows =array (
  0 => 
  array (
    'pLine' => 12,
    'key' => 'VA',
    'name' => 'Воробьёв Андрей',
    'color' => 'yellow',
    'hdr' => 'Воробьёв Андрей',
    'title' => 'Воробьёв Андрей',
  ),
  1 => 
  array (
    'pLine' => 13,
    'key' => 'GG',
    'name' => 'Гудков Геннадий',
    'color' => 'orange',
    'hdr' => 'Гудков Геннадий',
    'title' => 'Гудков Геннадий',
  ),
  2 => 
  array (
    'pLine' => 14,
    'key' => 'KN',
    'name' => 'Корнеева Надежда',
    'color' => '#ff77ff',
    'hdr' => 'Корнеева Надежда',
    'title' => 'Корнеева Надежда',
  ),
  3 => 
  array (
    'pLine' => 15,
    'key' => 'RA',
    'name' => 'Романович Александр',
    'color' => '#77ffff',
    'hdr' => 'Романович Александр',
    'title' => 'Романович Александр',
  ),
  4 => 
  array (
    'pLine' => 16,
    'key' => 'CK',
    'name' => 'Черемисов Константин',
    'color' => '#ffff77',
    'hdr' => 'Черемисов Константин',
    'title' => 'Черемисов Константин',
  ),
  5 => 
  array (
    'pLine' => 17,
    'key' => 'SM',
    'name' => 'Шингаркин Максим',
    'color' => 'red',
    'hdr' => 'Шингаркин Максим',
    'title' => 'Шингаркин Максим',
  ),
  6 => 
  array (
    'pLine' => 9,
    'key' => 'SP',
    'name' => 'Недействительные',
    'color' => 'grey',
    'hdr' => 'Недействительные',
    'title' => 'Недействительные',
  ),
);

            	$view->electionsName = 'Выборы губернатора Москвоской области. 08.09.2013';
				$folder = 'mosobl';
				$defaultRegion = '50';
            
            $JS_SCRIPT_VERSION = 1;
			$CSS_VERSION = 1;
			require(rtrim(TPL_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR. 'full.phtml');