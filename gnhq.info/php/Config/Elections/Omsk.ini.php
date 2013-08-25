<?php
$projects = array(
        PROJECT_GN => array(
                'Name'      => 'Гражданин наблюдатель',
        ),
        PROJECT_GOLOS => array(
                'Name'      => 'Голос',
        ),
);

$candidats = array(
        array('pLine' => 12, 'key' => 'AI', 'name' => 'Антропов Игорь', 'color' => 'yellow'),
        array('pLine' => 13, 'key' => 'DV', 'name' => 'Двораковский Вячеслав', 'color' => 'orange'),
        array('pLine' => 14, 'key' => 'GV', 'name' => 'Жарков Виктор', 'color' => '#ff77ff'),
        array('pLine' => 15, 'key' => 'ZY', 'name' => 'Зелинский Ян', 'color' => '#77ffff'),
        array('pLine' => 16, 'key' => 'KA', 'name' => 'Коротков Александр', 'color' => '#ffff77'),
        array('pLine' => 17, 'key' => 'MS', 'name' => 'Масленков Сергей', 'color' => 'red'),
        array('pLine' => 18, 'key' => 'OI', 'name' => 'Оверина Ирина', 'color' => 'blue'),
);

$electionName = 'omsk';
$electionTitle = 'Выборы мэра Омска.';
$maxLines = 19;

$config = array(
        'rootPage'   => 'http://www.omsk.vybory.izbirkom.ru/region/region/omsk?action=show&root=1&tvd=455422074950&vrn=455422074946&region=55&global=&sub_region=55&prver=0&pronetvd=null&vibid=455422074950&type=234',
        'name'       => 'Омск',
        'num'        => 55,
        'population' => 0
);