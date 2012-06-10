<?php
class ParserIKData_Site_KartaNarushenij extends ParserIKData_Site_Abstract
{

    public function getViolationXml($src)
    {
        $html = $this->_getLoader()->setSource($src)->load();
        $html = $this->_getParser()->stringInBetween($html, "<div id='violations-full'", '</nav>', true) . '</div>';
        $html = str_replace(array('<br>', '&nbsp;', "'"), array('<br/>', ' ', '"'), $html);
        $sXml = simplexml_load_string($html);
        if (!$sXml instanceof SimpleXMLElement) {
            return null;
        }
        $data = array();
        foreach ($sXml->xpath("div[@class='violation']") as $div) {
            $row = array();
            $row['id'] = (string)$div->attributes()->id;

            $types = array();
            foreach ($div->xpath("div[@class='vtypes block']/descendant::div/@title") as $tp) {
                $types[] = (string)$tp;
            }
            $row['types'] = $types;

            $descrBlock = $div->xpath("descendant::div[@class='description']");
            $descrBlock = $descrBlock[0];

            $ik = trim((string)$descrBlock->b);
            $ik = str_replace(array("\r", "\n", PHP_EOL), array(" ", " ", " "), $ik);
            $ik_parts = explode(' ', $ik);
            if ($ik_parts[0] == 'УИК') {
                $row['stationType'] = 'UIK';
                $row['stationNum'] = intval($ik_parts[2]);
            } elseif ($ik_parts[0] == 'ТИК') {
                $row['stationType'] = 'TIK';
                $row['stationNum'] = intval($ik_parts[2]);
            }
            $row['description'] = trim((string)$descrBlock->p);

            $data[] = $row;
        }

        $xml = "<?xml version='1.0'  encoding=\"UTF-8\"?>
        				<violations >";
        foreach ($data as $row) {
            $xml .= $this->_rowToXml($row);
        }
        $xml .= '</violations>';
        return simplexml_load_string($xml);
    }

    protected function _getConfigFileName()
    {
        throw new Exception('should not be used');
    }

    private function _rowToXml($row)
    {
        return "
         	<viol>
    			<id>".$row['id']."</id>
    			<obscomment>".$row['description']."</obscomment>
    			<region>24</region>
    			<stationtype>" . (isset($row['stationType']) ? $row['stationType'] : '') . "</stationtype>
    			<type>1</type>
    			<uik>" . (isset($row['stationNum']) ? $row['stationNum'] : '') . "</uik>
    			<updt>".date('y-m-d H:i:s')."</updt>
    			<version>1</version>
    		</viol>
            ";
    }
}