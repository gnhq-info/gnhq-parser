<?php
/**
* @author admin
*
* @method string getType()
* @method string getCode()
*
* @method ParserIKData_Model_Report412 setType()
* @method ParserIKData_Model_Report412 setCode()
*/
class ParserIKData_Model_Watch412 extends ParserIKData_Model
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::getUniqueId()
     */
    public function getUniqueId()
    {
        return intval($this->getFullName());
    }

    /**
    * @return ParserIKData_Model_UIK|null
    */
    public function getUik()
    {
        return ParserIKData_Model_UIK::getFromPool($this->getUikUniqueId());
    }

    /**
     * @return number
     */
    public function getUikUniqueId()
    {
        return $this->getUniqueId();
    }


    public function toArray()
    {
        $data = array();
        $data[] = $this->getUniqueId();
        $data[] = $this->getType();
        $data[] = $this->getCode();
        return $data;
    }

    public static function fromArray($arr)
    {
        $data = array();
        $data['FullName']       = $arr[0];
        $data['Type']           = $arr[1];
        $data['Code']           = $arr[2];
        $item = parent::fromArray($data);
        return $item;
    }

}