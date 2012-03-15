<?php
/**
 * @method int getOkrId
 * @method int getNum
 * @method int getIsBlacklist
 * @method ParserIKData_Model_MoscowCand setOkrId
 * @method ParserIKData_Model_MoscowCand setNum
 * @method ParserIKData_Model_MoscowCand setIsBlacklist
 * @author admin
 */
class ParserIKData_Model_MoscowCand extends ParserIKData_Model
{
    public function isBlacklist()
    {
        return $this->getIsBlacklist();
    }

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->getOkrId()  . '#' . $this->getNum();
    }


    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getOkrId();
        $data[] = $this->getNum();
        $data[] = $this->getFullName();
        $data[] = $this->getIsBlacklist();
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_MoscowCand
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['OkrId']       = $array[0];
        $data['Num']         = $array[1];
        $data['FullName']    = $array[2];
        $data['IsBlacklist'] = $array[3];
        return parent::fromArray($data);
    }

    protected function _setWebPageData($params)
    {
        $this->setNum($params['num']);
        $this->setOkrId($params['okrId']);
        $this->setIsBlacklist($params['isBlacklist']);
        return $this;
    }
}