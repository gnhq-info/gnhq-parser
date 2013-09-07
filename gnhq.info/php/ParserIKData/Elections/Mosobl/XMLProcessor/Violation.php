<?php
class ParserIKData_XMLProcessor_Violation_Mosobl extends ParserIKData_XMLProcessor_Violation
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
        return $this->_createFromStdClass($dataObj, 50);
    }

    /**
     * @return ParserIKData_Gateway_UIKRussia
     */
    protected function _getUikRGateway()
    {
        if ($this->_uikRGateway === null) {
            $this->_uikRGateway = new ParserIKData_Gateway_Uik_Mosobl();
            $this->_uikRGateway->setUseCache(true);
        }
        return $this->_uikRGateway;
    }

    protected function _prepareTime($xmlDate)
    {
        $ourdate = date(self::TIME_FORMAT, strtotime($xmlDate));
        return $ourdate;
    }

    protected function _getViolationGateway()
    {
        return new ParserIKData_Gateway_Violation_Mosobl();
    }
}