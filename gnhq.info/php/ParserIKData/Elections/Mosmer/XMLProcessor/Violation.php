<?php
class ParserIKData_XMLProcessor_Violation_Mosmer extends ParserIKData_XMLProcessor_Violation
{
    /**
     * @return stdClass|false
     */
    public function loadFromSource($src)
    {
        return $this->_loadFromJsonSource($src);
    }

    /**
     * @param stdClass $dataObj
     * @return ParserIKData_Model_Violation|string
     */
    public function createFromXml($dataObj)
    {
        return $this->_createFromStdClass($dataObj, 77);
    }

    /**
     * @return ParserIKData_Gateway_UIKRussia
     */
    protected function _getUikRGateway()
    {
        if ($this->_uikRGateway === null) {
            $this->_uikRGateway = new ParserIKData_Gateway_Uik_Mosmer();
            $this->_uikRGateway->setUseCache(true);
        }
        return $this->_uikRGateway;
    }

    protected function _prepareTime($xmlDate)
    {
        $ourdate = date(self::TIME_FORMAT, strtotime($xmlDate));
        return $ourdate;
    }

    protected function _getMergedType($projectCode, $projectType)
    {
        $mapping = array(
            2=>1, 3=>2, 4=>3, 5=>4, 6=>5, 7=>6, 8=>7, 9=>8, 10=>9, 11=>10, 12=>11, 13=>12, 14=>0, 15=>36,
            16=>13, 17=>14, 18=>15, 19=>16, 20=>17,
            21=>18, 22=>19, 23=>20, 24=>21, 25=>22, 26=>23, 27=>24, 28=>25, 29=>26, 30=>27, 31=>28, 32=>29,
            33=>30, 34=>31, 35=>32, 36=>34, 37=>33
        );
        $res = isset($mapping[intval($projectType)]) ? $mapping[intval($projectType)] : 0;
        return $res;
    }

    protected function _getViolationGateway()
    {
        return new ParserIKData_Gateway_Violation_Mosmer();
    }
}