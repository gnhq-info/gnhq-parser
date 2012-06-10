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
class ParserIKData_Model_Protocol_Krasnoyarsk extends ParserIKData_Model_Protocol
{
    const INDEX_EA = 13;
    const INDEX_MI = 14;
    const INDEX_NK = 15;
    const INDEX_AK = 16;
    const INDEX_AM = 17;
    const INDEX_MO = 18;
    const INDEX_AP = 19;


    public static function getLineAmount()
    {
        return 20;
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
        return array(self::INDEX_SPOILED, self::INDEX_TOTAL, self::INDEX_TOTAL_VOTED,
            self::INDEX_EA, self::INDEX_MI, self::INDEX_NK, self::INDEX_AK, self::INDEX_AM, self::INDEX_MO, self::INDEX_AP);
    }


    /**
     * @param boolean $inPercent
     * @param int $digits
     * @return array
     */
    public function getDiagramData($inPercent, $digits = 0)
    {
        $data = array('EA' => 0, 'MI' => 0, 'NK' => 0, 'AK' => 0, 'AM' => 0, 'MO' => 0, 'AP' => 0);
        $_absAtt = $this->_getAbsoluteAttendance();
        $_total = $this->_getPeopleAmount();
        if ($_absAtt == 0) {
            return $data;
        }
        $data['EA'] = $this->_getProtocolValue(self::INDEX_EA)/$_absAtt;
        $data['MI'] = $this->_getProtocolValue(self::INDEX_MI)/$_absAtt;
        $data['NK'] = $this->_getProtocolValue(self::INDEX_NK)/$_absAtt;
        $data['AK'] = $this->_getProtocolValue(self::INDEX_AK)/$_absAtt;
        $data['AM'] = $this->_getProtocolValue(self::INDEX_AM)/$_absAtt;
        $data['MO'] = $this->_getProtocolValue(self::INDEX_MO)/$_absAtt;
        $data['AP'] = $this->_getProtocolValue(self::INDEX_AP)/$_absAtt;
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