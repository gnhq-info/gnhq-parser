<?php
class ParserIKData_XMLProcessor_Protocol_Mosmer extends ParserIKData_XMLProcessor_Protocol
{
    /**
     * @param stdClass $oData
     * @return ParserIKData_Model_Protocol|string
     */
    public function createFromXml($oData)
    {
        $errors = array();
        if (!$oData instanceof stdClass) {
            return 'bad object';
        }

        $proto = $this->_getNewProtocol();

        // обязательные поля
        $proto->setResultType($this->_projectCode);
        // id in project
        if (!$oData->id) {
            $errors[] = 'Не указан id';
        } else {
            $proto->setProjectId($this->_filterString((string)$oData->id, 50));
        }

        // update time
        if (!$oData->updated_at) {
            $errors[] = 'Не указано время обновления';
        } else {
            $proto->setUpdateTime($this->_prepareTime((string)$oData->updated_at));
        }

        if ($oData->sums_valid !== true) {
            $errors[] = 'Не сходятся данные по участку';
        }

        // uik full
        $regionNum = 77;
        if(!$oData->uik) {
            $errors[] = 'Не указан номер УИК';
        } else {
            $uikNum = (int)$oData->uik;
        }
        $proto->setIkFullName($regionNum * ParserIKData_Model_UIK::UIKMODULE + $uikNum);

        // lines
        $mandatoryIndices = $this->_getMandatoryIndices();
        $lineData = array();

        $rowData = json_decode(json_encode($oData->rows), true);
        $rData = array();
        foreach ($rowData as $a) {
            foreach ($a as $k => $v) {
                $rData[$k] = $v;
            }
        }
        $rowData = $rData;

        for ($i = 1; $i < $this->_getLineAmount(); $i++) {
            $prtName = 'r' . $this->_mapIndices($i);
            if (!empty($rowData[$prtName])) {
                $lineData[$i] = (int)$rowData[$prtName];
            } else {
                if (in_array($i, $mandatoryIndices)) {
                    $errors[] = 'Не указано обязательное поле протокола '.$i;
                } else {
                    $lineData[$i] = 0;
                }
            }
        }
        $proto->setData($lineData);


        $proto->setClaimCount(0);

        // returning
        if (!empty($errors)) {
            return implode(', ' , $errors);
        } else {
            return $proto;
        }
    }


    /**
     * (non-PHPdoc)
     * @see ParserIKData_XMLProcessor_Protocol::_getLineAmount()
     */
    protected function _getLineAmount()
    {
        return ParserIKData_Model_Protocol_Mosmer::getLineAmount();
    }

    /**
     * @return ParserIKData_Gateway_Protocol
     */
    protected function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol_Mosmer();
    }

    /**
     * @return int[]
     */
    protected function _getMandatoryIndices()
    {
		//for testing
        return array(9, 10, 11, 12, 13, 14, 15);
        return array(9, 10, 12, 13, 14, 15, 16, 17);
	}

	/**
	 * @param int $i
	 * @return int
	 */
	private function _mapIndices($i)
	{
	    $map = array(9 => 10, 10 => 9, 16 => 11);
	    if (array_key_exists($i, $map)) {
	        return $map[$i];
	    } else {
	        return $i;
	    }
	}

	protected function _prepareTime($xmlDate)
	{
	    $date = $xmlDate;
	    $ourdate = date(self::TIME_FORMAT, strtotime($date));
	    return $ourdate;
	}

    /**
     * (non-PHPdoc)
     * @see ParserIKData_XMLProcessor_Protocol::_getNewProtocol()
     */
    protected function _getNewProtocol()
    {
        return ParserIKData_Model_Protocol_Mosmer::create();
    }
}