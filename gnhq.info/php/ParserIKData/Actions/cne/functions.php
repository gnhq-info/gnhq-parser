<?php
function prepareFileContent($content, $filename, $oldElection, $newElection)
{
    global $projects, $config, $electionTitle, $electionName, $candidats;
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
    switch ($last) {

        case 'Config.php' :
            $content = '<?php '.PHP_EOL .
            "\t\t".' if (!isset($PROJECT_CONFIG)) {' . PHP_EOL.
            "\t\t" . '$PROJECT_CONFIG ='. PHP_EOL ;
            $content .= var_export($projects, 1);
            $content .= ';}';
            break;

        case 'regions_data.js':
            $content = '
        		StaticData.Regions = {
					\''.$config['num'].'\': \''.$config['name'].'\'
				};
        		';
            break;

        case 'tik_data.js':
            $content = '';
            break;

        case 'watchers_data.js':
            $parts = array();
            $sparts = array();
            foreach ($projects as $k => $pr) {
                $parts[] = "'" . $k . "': '" . $pr['Name'] . "'";
                $sparts[] = "'" . $k . "': 1";
            }
            $fcode = implode(','.PHP_EOL."\t", $parts);
            $scode = implode(','.PHP_EOL."\t", $sparts);
            $content = "
StaticData.Watchers = {
	".$fcode."
};

StaticData.WatchersOnline = {
	".$scode."
};";
            break;

        case 'index.php':
            $content = '<?php
$view = new stdClass();
$view->diagRows =';
            $candForIndex = $candidats;
            $candForIndex[] = array('pLine' => 9, 'key' => 'SP', 'name' => 'Недействительные', 'color' => 'grey');
            foreach ($candForIndex as $i => $pr) {
                $candForIndex[$i]['hdr'] = $pr['name'];
                $candForIndex[$i]['title'] = $pr['name'];
            }
            $content .= var_export($candForIndex, 1);
            $content .= ';'.PHP_EOL;

            $content .= '
            	$view->electionsName = \''.$electionTitle.'\';
				$folder = \''.$electionName.'\';
				$defaultRegion = \''.$config['num'].'\';
            ';
            $content .= '
            $JS_SCRIPT_VERSION = 1;
			$CSS_VERSION = 1;
			require(rtrim(TPL_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR. \'full.phtml\');';
            break;

        default:
            break;
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
        "\t\t\t" .'$data["SP"] = 0;'.PHP_EOL .
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