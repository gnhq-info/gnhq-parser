<?php
class ParserIKData_XMLProcessor_Violation_Krasnoyarsk extends ParserIKData_XMLProcessor_Violation
{
    public function loadFromSource($src)
    {
        if ($this->_projectCode = PROJECT_GOLOS) {
            $html = file_get_contents($src);
            $parser = new Lib_Html_Parser();
            $html = $parser->stringInBetween($html, "<div id='violations-full'", '</nav>', true) . '</div>';
            $html = str_replace(array('<br>', '&nbsp;', "'"), array('<br/>', ' ', '"'), $html);
            $sXml = simplexml_load_string($html);
            $data = array();
            foreach ($sXml->xpath("div[@class='violation']") as $div) {
                $row = array();
                $row['id'] = (string)$div->attributes()->id;
                $data[] = $row;
            }

            var_dump($data);
            die();


        } else {
            return parent::loadFromSource($src);
        }
    }

    protected function _getViolationGateway()
    {
        return new ParserIKData_Gateway_Violation_Krasnoyarsk();
    }
}