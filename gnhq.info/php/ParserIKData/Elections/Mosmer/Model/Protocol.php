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
 * @method ParserIKData_Model_Protocol setData()
 * @method ParserIKData_Model_Protocol setClaimCount()
 * @method ParserIKData_Model_Protocol setProjectId()
 * @method ParserIKData_Model_Protocol setUpdateTime()
 * @method ParserIKData_Model_Protocol setSignTime()
 * @method ParserIKData_Model_Protocol setDirt()
 * @method ParserIKData_Model_Protocol setCopy()
 * @method ParserIKData_Model_Protocol setRevised()
 * @method ParserIKData_Model_Protocol setResultType()
 * @method ParserIKData_Model_Protocol setIkFullName()
 *
 * protocol (resultaty) vyborov po komissii
 * @author admin
 */
class ParserIKData_Model_Protocol_Mosmer extends ParserIKData_Model_Protocol
{
    public static function getLineAmount()
    {
		return 18;
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
		 return array(self::INDEX_SPOILED, self::INDEX_TOTAL, self::INDEX_TOTAL_VOTED, 12, 13, 14, 15, 16, 17);
	}


    /**
     * @param boolean $inPercent
     * @param int $digits
     * @return array
     */
    public function getDiagramData($inPercent, $digits = 0)
    {
		$data = array('DM' => 0,'LN' => 0,'MI' => 0,'MS' => 0,'NA' => 0,'SS' => 0);
		$_absAtt = $this->_getAbsoluteAttendance();
		$_total = $this->_getPeopleAmount();
		if ($_absAtt == 0) {
			$data["SP"] = 0;
			return $data;
		}
		$data['DM'] = $this->_getProtocolValue(12)/$_absAtt;
		$data['LN'] = $this->_getProtocolValue(13)/$_absAtt;
		$data['MI'] = $this->_getProtocolValue(14)/$_absAtt;
		$data['MS'] = $this->_getProtocolValue(15)/$_absAtt;
		$data['NA'] = $this->_getProtocolValue(16)/$_absAtt;
		$data['SS'] = $this->_getProtocolValue(17)/$_absAtt;
		$data['AT'] = intval($_total) != 0 ? $_absAtt/$_total : '?'; 
		$data['SP'] = $this->_getSpoiledAmount()/$_absAtt;
		$data = $this->_roundDiagramData($data, $inPercent, $digits);
		return $data;
	}
}