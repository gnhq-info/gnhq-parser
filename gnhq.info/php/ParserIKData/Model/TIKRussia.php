<?php
/**
 * @method int getRegionNum
 * @method int getTikNum
 * @method string getOkrugName
 * @method ParserIKData_Model_TIKRussia setRegionNum
 * @method ParserIKData_Model_TIKRussia setTikNum
 * @method ParserIKData_Model_TIKRussia setOkrugName
 * @author admin
 */
class ParserIKData_Model_TIKRussia extends ParserIKData_Model
{
    const MODULE = 1000;

    private $_discrepancyCount = 0;

    /**
     * @return number
     */
    public function getDiscrepancyCount()
    {
        return $this->_discrepancyCount;
    }

    /**
     * @param int $cnt
     * @return ParserIKData_Model_Okrug
     */
    public function setDiscrepancyCount($cnt)
    {
        $this->_discrepancyCount = intval($cnt);
        return $this;
    }



    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->getRegionNum() * self::MODULE + $this->getTikNum();
    }



    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getRegionNum();
        $data[] = $this->getTikNum();
        $data[] = $this->getFullName();
        $data[] = $this->getOkrugName();
        $data[] = $this->getLink();
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_TIKRussia
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['RegionNum'] = $array[0];
        $data['TikNum']    = $array[1];
        $data['FullName']  = $array[2];
        $data['OkrugName'] = $array[3];
        $data['Link']      = $array[4];
        return parent::fromArray($data);
    }

    protected function _setWebPageData($params)
    {
        $this->setRegionNum($params['regionNum']);
        $this->setTikNum($params['tikNum']);
        if (!empty($params['okrugName'])) {
            $this->setOkrugName($params['okrugName']);
        }
        return $this;
    }
}