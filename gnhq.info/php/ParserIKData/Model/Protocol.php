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
abstract class ParserIKData_Model_Protocol extends ParserIKData_Model
{
    const TYPE_GN    = 'GN';
    const TYPE_OF    = 'OF';
    const IkTYPE_UIK = 'UIK';
    const IkTYPE_TIK = 'TIK';
    const IkTYPE_OIK = 'OIK';

    const INDEX_SPOILED = 9;
    const INDEX_TOTAL_VOTED = 10;
    const INDEX_TOTAL = 1;

    const ALLOWABLE_DISCREPANCY = 10;

    private $_uikCount = 1;

    abstract public function getElectionType();

    abstract public static function getLineAmount();

    abstract public static function getIndicesForCompare();

    abstract public static function getAllowableDiscrepancy();

    abstract public function getDiagramData($inPercent, $digits = 0);



    public static function create()
    {
        return new self();
    }

    public function getUikCount()
    {
        return $this->_uikCount;
    }

    public function setUikCount($uikCount)
    {
        $this->_uikCount = $uikCount;
        return $this;
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
     * @return boolean
     */
    public function isEmpty()
    {
        $data = $this->getDiagramData(true, 0);
        if (array_sum($data)) {
            return false;
        } else {
            return true;
        }
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
        $data[] = ($this->getClaimCount() ? intval($this->getClaimCount()) : 0);
        $data[] = $this->getProjectId();
        $data[] = $this->getUpdateTime();
        $data[] = $this->getSignTime();
        $data[] = $this->getDirt();
        $data[] = $this->getCopy();
        $data[] = $this->getRevised();
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
        $data['IkFullName'] = array_shift($arr);
        $data['IkType']     = array_shift($arr);
        $data['Type']       = array_shift($arr);
        $data['ClaimCount'] = array_shift($arr);
        $data['ProjectId'] = array_shift($arr);
        $data['UpdateTime'] = array_shift($arr);
        $data['SignTime'] = array_shift($arr);
        $data['Dirt'] = array_shift($arr);
        $data['Copy'] = array_shift($arr);
        $data['Revised'] = array_shift($arr);
        array_unshift($arr, '');
        unset($arr[0]);
        $data['Data']     = $arr;
        $data['Link']     = '';
        $item = parent::fromArray($data);
        return $item;
    }


    /**
    * @param int $index
    * @return int
    */
    protected function _getProtocolValue($index)
    {
        $data = $this->getData();
        return $data[$index];
    }

    protected function _getAbsoluteAttendance()
    {
        return $this->_getSpoiledAmount() + $this->_getVotedAmount();
    }

    protected function _getVotedAmount()
    {
        return $this->_getProtocolValue(self::INDEX_TOTAL_VOTED);
    }

    protected function _getSpoiledAmount()
    {
        return $this->_getProtocolValue(self::INDEX_SPOILED);
    }

    protected function _getPeopleAmount()
    {
        return $this->_getProtocolValue(self::INDEX_TOTAL);
    }

}