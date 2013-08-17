<?php
include_once 'include.php';

$code = $argv[1];
if (!ctype_alpha($code)) {
    die('bad elections code');
}
$electionsConfigFile = INI_CONFIG_PATH . DIRECTORY_SEPARATOR . 'Elections' . DIRECTORY_SEPARATOR . $code . '.ini.php';

include $electionsConfigFile;

/* functions */
require_once 'cne'.DIRECTORY_SEPARATOR.'functions.php';

/* create ini config */
require_once('cne'.DIRECTORY_SEPARATOR.'config.php');

/* create tables in database */
require_once('cne'.DIRECTORY_SEPARATOR.'database.php');

/* create backend files */
require_once('cne'.DIRECTORY_SEPARATOR.'backend.php');

/* create backend files */
require_once('cne'.DIRECTORY_SEPARATOR.'frontend.php');

/* create backend files */
require_once('cne'.DIRECTORY_SEPARATOR.'insert.php');

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
