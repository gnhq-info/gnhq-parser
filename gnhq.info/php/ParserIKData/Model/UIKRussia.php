<?php
/**
 * @method int getRegionNum
 * @method int getTikNum
 * @method int getUikNum
 * @method string getPlace
 * @method string getVotingPlace
 * @method string getBorderDescription
 * @method ParserIKData_Model_UIKRussia setRegionNum
 * @method ParserIKData_Model_UIKRussia setTikNum
 * @method ParserIKData_Model_UIKRussia setUikNum
 * @method ParserIKData_Model_UIKRussia setPlace
 * @method ParserIKData_Model_UIKRussia setVotingPlace
 * @method ParserIKData_Model_UIKRussia setBorderDescription
 * @author admin
 */
class ParserIKData_Model_UIKRussia extends ParserIKData_Model
{
    const MODULE = 10000;
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->getFullName();
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
        $data[] = $this->getUikNum();
        $data[] = $this->getFullName();
        $data[] = $this->getLink();
        $data[] = $this->getPlace();
        $data[] = $this->getVotingPlace();
        $data[] = $this->getBorderDescription();
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_UIKRussia
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['RegionNum']         = $array[0];
        $data['TikNum']            = $array[1];
        $data['UikNum']            = $array[2];
        $data['FullName']          = $array[3];
        $data['Link']              = $array[4];
        $data['Place']             = $array[5];
        $data['VotingPlace']       = $array[6];
        $data['BorderDescription'] = $array[7];
        return parent::fromArray($data);
    }

    protected function _setWebPageData($params)
    {
        $this->setRegionNum($params['regionNum']);
        $this->setTikNum($params['tikNum']);
        $this->setUikNum($params['uikNum']);
        $this->setFullName($params['regionNum'] * self::MODULE + $params['uikNum']);
        return $this;
    }
}