<?php
/**
* @author admin
*
* @method string getOcenka()
* @method string getAuthor()
* @method string getShort()
* @method string getFullReport()
*
* @method ParserIKData_Model_Report412 setOcenka()
* @method ParserIKData_Model_Report412 setAuthor()
* @method ParserIKData_Model_Report412 setShort()
* @method ParserIKData_Model_Report412 setFullReport()
*/
class ParserIKData_Model_Report412 extends ParserIKData_Model
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
        $data[] = $this->getOcenka();
        $data[] = $this->getAuthor();
        $data[] = $this->getShort();
        $data[] = $this->getFullReport();
        $data[] = $this->getLink();
        return $data;
    }

    public static function fromArray($arr)
    {
        $data = array();
        $data['FullName']       = $arr[0];
        $data['Ocenka']         = $arr[1];
        $data['Author']         = $arr[2];
        $data['Short']          = $arr[3];
        $data['FullReport']     = $arr[4];
        $data['Link']           = $arr[5];
        $item = parent::fromArray($data);
        return $item;
    }

}