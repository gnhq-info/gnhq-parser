<?php
$sampleName = 'Krasnoyarsk';
$newName = ucfirst(strtolower($electionName));
$fromDir = APPLICATION_DIR_ROOT . 'Elections' . DIRECTORY_SEPARATOR . $sampleName;

$dirs = array($fromDir);
$files = array();

getDirectoryContents($fromDir, $dirs, $files);

foreach ($dirs as $dir) {
    $ndir = getNewElectionFname($dir, $sampleName, $newName);
    if (!file_exists($ndir)) {
        print 'created directory: '.$ndir . PHP_EOL;
        mkdir($ndir);
    }

}

foreach ($files as $fname => $content) {
    $nfname = getNewElectionFname($fname, $sampleName, $newName);


    file_put_contents($nfname, prepareFileContent($content, $fname, $sampleName, $newName));
    print 'created file: ' . $nfname . PHP_EOL;
}


