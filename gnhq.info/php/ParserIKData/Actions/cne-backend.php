<?php
$sampleName = 'Krasnoyarsk';
$newName = ucfirst(strtolower($electionName));
$fromDir = APPLICATION_DIR_ROOT . DIRECTORY_SEPARATOR . 'Elections' . DIRECTORY_SEPARATOR . $sampleName;

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


function prepareFileContent($content, $filename, $oldElection, $newElection)
{
    $content = str_replace(strtolower($oldElection), strtolower($newElection), $content);
    $content = str_replace(ucfirst(strtolower($oldElection)), ucfirst(strtolower($newElection)), $content);
    $fileNameParts = explode(DIRECTORY_SEPARATOR, $filename);
    $last = array_pop($fileNameParts);
    $preLast = array_pop($fileNameParts);
    if ($last == 'Protocol.php') {
        switch ($preLast) {
            case 'Model':
                $content = prepareModelProtocolCode($content);
                break;
            case 'Gateway':
                $content = prepareGatewayProtocolCode($content);
                break;
            case 'XMLProcessor':
                $content = prepareXmlProcessorProtocolCode($content);
                break;
            default:
                break;
        }
    }
    return $content;
}

function prepareModelProtocolCode($code)
{
    global $candidats;
    global $maxLines;

    $newCode = '';
    $funcStart = mb_strpos($code, 'getLineAmount()');
    $open = mb_strpos($code, '{', $funcStart);
    $close = mb_strpos($code, '}', $open);
    $newCode .= mb_substr($code, 0, $open) . '{' . PHP_EOL;
    $newCode .= "\t\t" . 'return '.$maxLines.';' . PHP_EOL . "\t";

    $code = mb_substr($code, $close);
    $funcStart = mb_strpos($code, 'getIndicesForCompare()');
    $open = mb_strpos($code, '{', $funcStart);
    $close = mb_strpos($code, '}', $open);
    $newCode .= mb_substr($code, 0, $open) . '{' . PHP_EOL;
    $newCode .= "\t\t return array(self::INDEX_SPOILED, self::INDEX_TOTAL, self::INDEX_TOTAL_VOTED";
    foreach ($candidats as $cand) {
        $newCode .= ', '. $cand['pLine'];
    }
    $newCode .= ');' . PHP_EOL . "\t";
    $code = mb_substr($code, $close);

    $funcStart = mb_strpos($code, 'getDiagramData(');
    $open = mb_strpos($code, '{', $funcStart);
    $newCode .= mb_substr($code, 0, $open) . '{' . PHP_EOL;
    $newCode .= "\t\t" . '$data = array(';
    $parts = array();
    foreach ($candidats as $cand) {
        $parts[] = '\''.$cand['key'].'\' => 0';
    }
    $newCode .= implode(',', $parts) .  ');' . PHP_EOL;
    $newCode .=
        "\t\t" .'$_absAtt = $this->_getAbsoluteAttendance();'.PHP_EOL .
        "\t\t" .'$_total = $this->_getPeopleAmount();'.PHP_EOL .
        "\t\t" .'if ($_absAtt == 0) {'.PHP_EOL .
        "\t\t\t" .'return $data;'.PHP_EOL .
        "\t\t" . '}' . PHP_EOL;

    foreach ($candidats as $cand) {
        $newCode .= "\t\t" . '$data[\'' .$cand['key']. '\'] = $this->_getProtocolValue('.$cand['pLine'].')/$_absAtt;'.PHP_EOL;
    }

    $newCode .=
        "\t\t" . '$data[\'AT\'] = intval($_total) != 0 ? $_absAtt/$_total : \'?\'; ' . PHP_EOL .
    	"\t\t" . '$data[\'SP\'] = $this->_getSpoiledAmount()/$_absAtt;' . PHP_EOL .
        "\t\t" . '$data = $this->_roundDiagramData($data, $inPercent, $digits);' . PHP_EOL .
        "\t\t" . 'return $data;'.PHP_EOL;

    $newCode .= "\t}" . PHP_EOL . '}';
    return $newCode;
}

function prepareGatewayProtocolCode($code)
{
    global $candidats;
    $funcStart = mb_strpos($code, '_getCondControlRel()');
    $openBrack = mb_strpos($code, '{', $funcStart);
    $closeBrack = mb_strpos($code, '}', $openBrack);
    $funcCode = PHP_EOL ."\t\t" . 'return \'(Line10 = ';
    foreach ($candidats as $cand) {
        $funcCode .= 'Line' . $cand['pLine'] . ' + ';
    }
    $funcCode = substr($funcCode, 0, -2);
    $funcCode .= ')\';' . PHP_EOL . "\t";

    return mb_substr($code, 0, $openBrack) . '{' . $funcCode . mb_substr($code, $closeBrack);
}

function prepareXmlProcessorProtocolCode($code)
{
    global $candidats;
    $funcStart = mb_strpos($code, '_getMandatoryIndices()');
    $openBrack = mb_strpos($code, '{', $funcStart);
    $closeBrack = mb_strpos($code, '}', $openBrack);
    $funcCode = PHP_EOL ."\t\t" . 'return array(9, 10';
    foreach ($candidats as $cand) {
        $funcCode .= ', ' . $cand['pLine'];
    }
    $funcCode .= ');' . PHP_EOL . "\t";

    return mb_substr($code, 0, $openBrack) . '{' . $funcCode . mb_substr($code, $closeBrack);
}