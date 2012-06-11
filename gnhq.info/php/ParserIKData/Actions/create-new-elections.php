<?php
include_once 'include.php';

$candidats = array(
    array('pLine' => 13, 'key' => 'AI', 'name' => 'Антропов Игорь', 'color' => 'yellow'),
    array('pLine' => 14, 'key' => 'DV', 'name' => 'Двораковский Вячеслав', 'color' => 'orange'),
    array('pLine' => 15, 'key' => 'ZV', 'name' => 'Жарков Виктор', 'color' => '#ff77ff'),
    array('pLine' => 16, 'key' => 'ZJ', 'name' => 'Зелинский Ян', 'color' => '#77ffff'),
    array('pLine' => 17, 'key' => 'KA', 'name' => 'Коротков Александр', 'color' => '#ffff77'),
    array('pLine' => 18, 'key' => 'MS', 'name' => 'Масленков Сергей', 'color' => 'red'),
    array('pLine' => 19, 'key' => 'OI', 'name' => 'Оверина Ирина', 'color' => '#66ee00'),
);

$electionName = 'omsk';
$maxLines = 20;

$config = array(
    'rootPage' => 'http://www.omsk.vybory.izbirkom.ru/region/omsk?action=show&root_a=null&vrn=455422074946&region=55&global=&type=0&prver=0&pronetvd=null',
    'name' => 'Омск',
    'num'  => 55,
    'population' => 0
);


require_once('cne-config.php');

require_once('cne-database.php');




/* create backend files */
require_once('cne-backend.php');


function getNewElectionFname($fname, $sampleName, $newName)
{
    return str_replace(DIRECTORY_SEPARATOR .$sampleName, DIRECTORY_SEPARATOR . $newName, $fname);
}

function getDirectoryContents($path, &$dirs, &$files) {
    $iterator = new DirectoryIterator($path);
    foreach ($iterator as $fileInfo) {
        /* @var $fileInfo SplFileInfo*/
        if ($fileInfo->isDot()) continue;
        $fullName = $fileInfo->getPathname();
        if ($fileInfo->isDir()) {
            $dirs[] = $fullName;
            getDirectoryContents($fullName, $dirs, $files);
        } else {
            $files[$fullName] = file_get_contents($fullName);
        }
    }
}
