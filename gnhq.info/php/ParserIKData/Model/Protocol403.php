<?php
/**
 * @method array getData()        'результаты'
 * @method int|null getClaimCount()
 * @method string getProjectId()
 * @method datetime getUpdateTime()
 * @method datetime getSignTime()
 * @method int getDirt()
 * @method int getCopy()
 * @method int getRevised()
 * @method int getResultType()
 * @method int getIkFullName()
 *
 * @method ParserIKData_Model_Protocol403 setData()
 * @method ParserIKData_Model_Protocol403 setClaimCount()
 * @method ParserIKData_Model_Protocol403 setProjectId()
 * @method ParserIKData_Model_Protocol403 setUpdateTime()
 * @method ParserIKData_Model_Protocol403 setSignTime()
 * @method ParserIKData_Model_Protocol403 setDirt()
 * @method ParserIKData_Model_Protocol403 setCopy()
 * @method ParserIKData_Model_Protocol403 setRevised()
 * @method ParserIKData_Model_Protocol403 setResultType()
 * @method ParserIKData_Model_Protocol403 setIkFullName()
 *
 * protocol (resultaty) vyborov po komissii
 * @author admin
 */
class ParserIKData_Model_Protocol403 extends ParserIKData_Model_Protocol
{
    private $_electionType = '403';

    const INDEX_VZ = 19;
    const INDEX_GZ = 20;
    const INDEX_SM = 21;
    const INDEX_MP = 22;
    const INDEX_VP = 23;

    public static function create()
    {
        return new self();
    }

    /**
     * @return string
     */
    public function getElectionType()
    {
        return $this->_electionType;
    }


    public static function getLineAmount()
    {
        return 24;
    }

    public static function getAllowableDiscrepancy()
    {
        return 10;
    }


    /**
     * @return array()
     */
    public static function getIndicesForCompare()
    {
        return array(
            self::INDEX_SPOILED, self::INDEX_TOTAL, self::INDEX_TOTAL_VOTED,
            self::INDEX_VZ, self::INDEX_GZ, self::INDEX_SM, self::INDEX_MP, self::INDEX_VP);
    }


    /**
     * @param boolean $inPercent
     * @param int $digits
     * @return array
     */
    public function getDiagramData($inPercent, $digits = 0)
    {
        $data = array('VZ' => 0, 'GZ' => 0, 'SM' => 0, 'MP' => 0, 'VP' => 0, 'AT' => 0, 'SP' => 0);
        $_absAtt = $this->_getAbsoluteAttendance();
        $_total = $this->_getPeopleAmount();
        if ($_absAtt == 0) {
            return $data;
        }
        $data['VZ'] = $this->_getProtocolValue(self::INDEX_VZ)/$_absAtt;
        $data['GZ'] = $this->_getProtocolValue(self::INDEX_GZ)/$_absAtt;
        $data['SM'] = $this->_getProtocolValue(self::INDEX_SM)/$_absAtt;
        $data['MP'] = $this->_getProtocolValue(self::INDEX_MP)/$_absAtt;
        $data['VP'] = $this->_getProtocolValue(self::INDEX_VP)/$_absAtt;
        $data['AT'] = intval($_total) != 0 ? $_absAtt/$_total : '?';
        $data['SP'] = $this->_getSpoiledAmount()/$_absAtt;

        foreach ($data as $k => $value) {
            if ($inPercent) {
                $val = 100 * $value;
            } else {
                $val = $value;
            }
            $data[$k] = round($val, $digits);
        }

        return $data;
    }
}