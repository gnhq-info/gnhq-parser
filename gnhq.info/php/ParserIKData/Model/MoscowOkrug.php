<?php
/**
 * @method int getId
 * @method int getTikNum
 * @method int getMagnitude
 * @method ParserIKData_Model_MoscowOkrug setId
 * @method ParserIKData_Model_MoscowOkrug setTikNum
 * @method ParserIKData_Model_MoscowOkrug setMagnitude
 * @author admin
 */
class ParserIKData_Model_MoscowOkrug extends ParserIKData_Model
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->getId();
    }


    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getId();
        $data[] = $this->getFullName();
        $data[] = $this->getTikNum();
        $data[] = $this->getLink();
        $data[] = $this->getMagnitude();
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_UIKRussia
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['Id']         = $array[0];
        $data['FullName']   = $array[1];
        $data['TikNum']     = $array[2];
        $data['Link']       = $array[3];
        $data['Magnitude']  = $array[4];
        return parent::fromArray($data);
    }

    protected function _setWebPageData($params)
    {
        $this->setId($params['id']);
        $this->setTikNum($params['tikNum']);
        $this->setMagnitude($params['magnitude']);
        return $this;
    }
}