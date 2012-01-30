<?php
/**
 * @method array getData()        'результаты'
 *
 * @method ParserIKData_Model_Protocol412 setData()
 *
 * protocol (resultaty) vyborov po komissii
 * @author admin
 */
class ParserIKData_Model_Protocol412 extends ParserIKData_Model
{
    const TYPE_GN    = 'GN';
    const TYPE_OF    = 'OF';
    const IkTYPE_UIK = 'UIK';
    const IkTYPE_TIK = 'TIK';
    const IkTYPE_OIK = 'OIK';
    const ELECTION_TYPE = '412';
    const INDEX_SR = 19;
    const INDEX_LDPR = 20;
    const INDEX_PR = 21;
    const INDEX_KPRF = 22;
    const INDEX_YABLOKO = 23;
    const INDEX_ER = 24;
    const INDEX_PD = 25;

    /**
     * @return ParserIKData_Model_UIK|NULL
     */
    public function getUik()
    {
        return ParserIKData_Model_UIK::getFromPool($this->getFullName());
    }

    /**
     * @return string
     */
    public function getElectionType()
    {
        return self::ELECTION_TYPE;
    }

    /**
    * @param ParserIKData_Model_Protocol412 $cmpResult
    * @param int[] $indArray
    * @return boolean
    */
    public function equalResults($cmpResult, $indArray = array())
    {
        $dt = $this->getData();
        $cmpData = $cmpResult->getData();
        $equal = true;
        foreach ($dt as $i => $val) {
            if (empty($indArray) || in_array($i, $indArray)) {
                if (!isset($cmpData[$i])) {
                    $equal = false; break;
                }
                if ($val != $cmpData[$i]) {
                    $equal = false; break;
                }
            }
        }
        return $equal;
    }

    /**
     * @param ParserIKData_Model_Protocol412 $cmpResult
     */
    public function equalPartyResults($cmpResult)
    {
        return $this->equalResults($cmpResult, $this->_getPartyIndices());
    }

    /**
     * @return array
     */
    public function getPartyResults()
    {
        $data = $this->getData();
        $partyIndices = $this->_getPartyIndices();
        foreach ($data as $i => $k) {
            if (!in_array($i, $partyIndices)) {
                unset($data[$i]);
            }
        }
        return $data;
    }

    /**
     * @return int
     */
    public function getSRResult()
    {
        return $this->_getProtocolValue(self::INDEX_SR);
    }

    /**
    * @return int
    */
    public function getLDPRResult()
    {
        return $this->_getProtocolValue(self::INDEX_LDPR);
    }

    /**
    * @return int
    */
    public function getYablokoResult()
    {
        return $this->_getProtocolValue(self::INDEX_YABLOKO);
    }

    /**
    * @return int
    */
    public function getKPRFResult()
    {
        return $this->_getProtocolValue(self::INDEX_KPRF);
    }

    /**
    * @return int
    */
    public function getERResult()
    {
        return $this->_getProtocolValue(self::INDEX_ER);
    }

    /**
    * @return int
    */
    public function getPDResult()
    {
        return $this->_getProtocolValue(self::INDEX_PD);
    }

    /**
    * @return int
    */
    public function getPRResult()
    {
        return $this->_getProtocolValue(self::INDEX_PR);
    }


    /**
     * @return Ambigous <ParserIKData_Model, NULL>
     */
    public function getDualProtocol()
    {
        return self::getFromPool($this->_getDualUniqueId());
    }

    public function getUniqueId()
    {
        return $this->getFullName() . ':' . $this->getIkType() . ':' . $this->getType();
    }

    public function isTypeGn()
    {
        return $this->_properties['Type'] == self::TYPE_GN;
    }

    public function isTypeOf()
    {
        return $this->_properties['Type'] == self::TYPE_OF;
    }

    public function setTypeGn()
    {
        $this->_properties['Type'] = self::TYPE_GN;
        return $this;
    }

    public function setTypeOf()
    {
        $this->_properties['Type'] = self::TYPE_OF;
        return $this;
    }

    public function setIkTypeUIK()
    {
        $this->_properties['IkType'] = self::IkTYPE_UIK;
    }

    public function setIkTypeTIK()
    {
        $this->_properties['IkType'] = self::IkTYPE_TIK;
    }

    public function setIkTypeOIK()
    {
        $this->_properties['IkType'] = self::IkTYPE_OIK;
    }

    /**
     * @param string $resultType
     * @param ParserIKData_Model_UIK $uik
     * @return ParserIKData_Model_Protocol412
     */
    public static function getUikResult($resultType, $uik)
    {
        $uniqueId = self::_formUniqueId($uik->getFullName(), self::IkTYPE_UIK, $resultType);
        return ParserIKData_Model_Protocol412::getFromPool($uniqueId);
    }

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getFullName();
        $data[] = $this->getIkType();
        $data[] = $this->getType();
        $data = array_merge($data, $this->getData());
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::fromArray()
     */
    public static function fromArray($arr)
    {
        $data = array();
        $data['FullName'] = array_shift($arr);
        $data['IkType']   = array_shift($arr);
        $data['Type']     = array_shift($arr);
        array_unshift($arr, '');
        unset($arr[0]);
        $data['Data']     = $arr;
        $data['Link']     = '';
        $item = parent::fromArray($data);
        return $item;
    }

    /**
    * @return string
    */
    private function _getDualUniqueId()
    {
        return self::_formUniqueId($this->getFullName(),  $this->getIkType(), ($this->isTypeGn() ? self::TYPE_OF : self::TYPE_GN));
    }

    /**
     * @param string $fullName
     * @param string $ikType
     * @param string $resultType
     * @return string
     */
    private static function _formUniqueId($fullName, $ikType, $resultType)
    {
        return $fullName . ':' . $ikType . ':' . $resultType;
    }

    /**
    * @param int $index
    * @return int
    */
    private function _getProtocolValue($index)
    {
        $data = $this->getData();
        return $data[$index];
    }

    private function _getPartyIndices()
    {
        return array(self::INDEX_ER, self::INDEX_KPRF, self::INDEX_LDPR, self::INDEX_PD, self::INDEX_PR, self::INDEX_SR, self::INDEX_YABLOKO);
    }

}