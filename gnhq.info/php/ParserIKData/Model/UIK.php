<?php
/**
* @author admin
* @method string getTikUniqueId
* @method ParserIKData_Model_UIK setTikUniqueId
*
* @method string getBorderDescription()
* @method string getPlace()
* @method string getVotingPlace()
*
* @method ParserIKData_Model_UIK setBorderDescription()
* @method ParserIKData_Model_UIK setPlace()
* @method ParserIKData_Model_UIK setVotingPlace()
*/
class ParserIKData_Model_UIK extends ParserIKData_Model
{
    const UIKMODULE = 10000;

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::getUniqueId()
     */
    public function getUniqueId()
    {
        return intval($this->getFullName());
    }

    /**
    * @return ParserIKData_Model_TIK|null
    */
    public function getTik()
    {
        return ParserIKData_Model_TIK::getFromPool($this->getTikUniqueId());
    }

    public function getNumber()
    {
        return $this->getFullName() % self::UIKMODULE;
    }

    /**
     * only for calling from ParserIKData_Model_TIK->addUik
     * @param ParserIKData_Model_TIK $tik
     * @return ParserIKData_Model_UIK
     */
    public function _friendSetTik(ParserIKData_Model_TIK $tik)
    {
        return $this->setTikUniqueId($tik->getUniqueId());
    }

    /**
     * @param string $resultType
     * @return Ambigous <ParserIKData_Model_Protocol412, NULL>
     */
    public function getElection412Result($resultType)
    {
        return ParserIKData_Model_Protocol412::getUikResult($resultType, $this);
    }

    public function toArray()
    {
        $data = array();
        $data[] = $this->getTikUniqueId();
        $data[] = $this->getFullName();
        $data[] = $this->getBorderDescription();
        $data[] = $this->getPlace();
        $data[] = $this->getVotingPlace();
        $data[] = $this->getLink();
        return $data;
    }

    public static function fromArray($arr)
    {
        $data = array();
        $data['TikUniqueId']       = $arr[0];
        $data['FullName']          = $arr[1];
        $data['BorderDescription'] = $arr[2];
        $data['Place']             = $arr[3];
        $data['VotingPlace']       = $arr[4];
        $data['Link']              = $arr[5];
        $item = parent::fromArray($data);
        /* @var $item ParserIKData_Model_UIK*/
        if ($item->getTik()) {
            $item->getTik()->addUik($item);
        }
        return $item;
    }

}