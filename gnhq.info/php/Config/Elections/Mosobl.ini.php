<?php
$projects = array(
		PROJECT_GN => array(
		    'Name' => 'Гражданин наблюдатель',
		    'ProtoLink' => '',
		    'ViolLink' => ''
		),
		PROJECT_GOLOS => array(
		    'Name' => 'Голос',
			'ProtoLink' => 'http://sms-cik.org/elections/152/export_json.json',
			'ViolLink' => ''
		)
    );

$candidats = array(
		array('pLine' => 12, 'key' => 'VA', 'name' => 'Воробьёв Андрей',
				'color' => 'yellow'),
		array('pLine' => 13, 'key' => 'GG', 'name' => 'Гудков Геннадий',
				'color' => 'orange'),
		array('pLine' => 14, 'key' => 'KN', 'name' => 'Корнеева Надежда',
				'color' => '#ff77ff'),
		array('pLine' => 15, 'key' => 'RA', 'name' => 'Романович Александр',
				'color' => '#77ffff'),
		array('pLine' => 16, 'key' => 'CK', 'name' => 'Черемисов Константин',
				'color' => '#ffff77'),
		array('pLine' => 17, 'key' => 'SM', 'name' => 'Шингаркин Максим',
				'color' => 'red'),);

$electionName = 'mosobl';
$electionTitle = 'Выборы губернатора Москвоской области. 08.09.2013';
$maxLines = 18;

$config = array(
		'rootPage' => 'http://www.moscow_reg.vybory.izbirkom.ru/region/moscow_reg?action=show&root_a=504001001&vrn=75070001571767&region=50&global=null&type=0&prver=0&pronetvd=null',
		'name' => 'Московская область', 'num' => 50, 'population' => 0);