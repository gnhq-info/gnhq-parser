<?php
/**
 * @author admin
 * @method string getDescription
 * @method datetime getTime
 * @method string getGuid
 * @method string getSource
 * @method string getPlace
 * @method ParserIKData_Model_Twit setDescription
 * @method ParserIKData_Model_Twit setTime
 * @method ParserIKData_Model_Twit setGuid
 * @method ParserIKData_Model_Twit setSource
 * @method ParserIKData_Model_Twit setPlace
 *
 */
class ParserIKData_Model_Twit extends ParserIKData_Model
{
    public function getUniqueId()
    {
        return $this->getGuid();
    }

    /**
    * (non-PHPdoc)
    * @see ParserIKData_Model::toArray()
    */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getGuid();
        $data[] = $this->getFullName();
        $data[] = $this->getDescription();
        $data[] = $this->getTime();
        $data[] = $this->getLink();
        $data[] = $this->getSource();
        $data[] = $this->getPlace();
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_Twit
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['Guid']        = $array[0];
        $data['FullName']    = $array[1];
        $data['Description'] = $array[2];
        $data['Time']        = $array[3];
        $data['Link']        = $array[4];
        $data['Source']      = $array[5];
        $data['Place']       = $array[6];
        return parent::fromArray($data);
    }

    protected function _setWebPageData($params)
    {
        $this->setDescription($params['description']);
        $this->setGuid($params['guid']);
        $this->setTime($params['time']);
        if (isset($params['source'])) {
            $this->setSource($params['source']);
        }
        if (isset($params['place'])) {
            $this->setPlace($params['place']);
        }
        return $this;
    }
}