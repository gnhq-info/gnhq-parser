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
        array('pLine' => 12, 'key' => 'DM', 'name' => 'Дегтярев Михаил', 'color' => 'yellow'),
        array('pLine' => 13, 'key' => 'LN', 'name' => 'Левичев Николай', 'color' => 'orange'),
        array('pLine' => 14, 'key' => 'MI', 'name' => 'Мельников Иван', 'color' => '#ff77ff'),
        array('pLine' => 15, 'key' => 'MS', 'name' => 'Митрохин Сергей', 'color' => '#77ffff'),
        array('pLine' => 16, 'key' => 'NA', 'name' => 'Навальный Алексей', 'color' => '#ffff77'),
        array('pLine' => 17, 'key' => 'SS', 'name' => 'Собянин Сергей', 'color' => 'red'),
);

$electionName = 'mosmer';
$electionTitle = 'Выборы мэра Москвы. 08.09.2013';
$maxLines = 18;

$config = array(
        'rootPage'   => 'http://www.moscow_city.vybory.izbirkom.ru/region/region/moscow_city?action=show&root=1&tvd=27720001368293&vrn=27720001368289&region=77&global=null&sub_region=77&prver=0&pronetvd=null&vibid=27720001368293&type=234',
        'name'       => 'Москва',
        'num'        => 77,
        'population' => 0
);