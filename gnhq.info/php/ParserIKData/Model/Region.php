<?php
/**
 * @method int getRegionNum
 * @method int getPopulation
 * @method ParserIKData_Model_Okrug setRegionNum
 * @method ParserIKData_Model_Okrug setPopulation
 * @author admin
 */
class ParserIKData_Model_Region extends ParserIKData_Model
{
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
        return $this->getRegionNum();
    }



    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getRegionNum();
        $data[] = $this->getFullName();
        $data[] = $this->getLink();
        $data[] = $this->getPopulation();
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_Okrug
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['RegionNum']  = $array[0];
        $data['FullName']   = $array[1];
        $data['Link']       = $array[2];
        $data['Population'] = $array[3];
        return parent::fromArray($data);
    }

    protected function _setWebPageData($params)
    {
        $this->setRegionNum($params['num']);
        return $this;
    }
}