<?php
$projects = array(
        PROJECT_GN => array(
                'Name'      => 'Гражданин наблюдатель',
                'ProtoLink' => 'https://gnhq.info/comm/protocols.xml'
                'ViolLink'  => ''
        ),
        PROJECT_GOLOS => array(
                'Name'      => 'Голос',
                'ViolLink'  => 'http://m.kartanarusheniy.org/messages/date/2012-06-10/56.0141053,92.8643491/56.0141053,92.8643491/geo',
                'ProtoLink' => ''
        ),
);

$candidats = array(
        array('pLine' => 13, 'key' => 'EA', 'name' => 'Эдхам Акбулатов', 'color' => 'yellow'),
        array('pLine' => 14, 'key' => 'MI', 'name' => 'Максим Иваныч', 'color' => 'orange'),
        array('pLine' => 15, 'key' => 'NK', 'name' => 'Нина Коврова', 'color' => '#ff77ff'),
        array('pLine' => 16, 'key' => 'AK', 'name' => 'Александр Коропачинский', 'color' => '#77ffff'),
        array('pLine' => 17, 'key' => 'AM', 'name' => 'Алексей Мещеряков', 'color' => '#ffff77'),
        array('pLine' => 18, 'key' => 'MO', 'name' => 'Михаил Осколков', 'color' => 'red'),
        array('pLine' => 19, 'key' => 'AP', 'name' => 'Алексей Подкорытов', 'color' => 'blue'),
);

$electionName = 'krasnoyarsk';
$electionTitle = 'Выборы мэра Красноярска.';
$maxLines = 20;

$config = array(
        'rootPage'   => 'http://www.krasnoyarsk.vybory.izbirkom.ru/region/region/krasnoyarsk?action=show&root=1&tvd=424422086839&vrn=424422086835&region=24&global=null&sub_region=24&prver=0&pronetvd=null&vibid=424422086839&type=234',
        'name'       => 'Красноярск',
        'num'        => 24,
        'population' => 5281164
);