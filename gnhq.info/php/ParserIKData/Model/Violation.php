<?php
/**
 * @method int getProjectId
 * @method string getProjectCode
 * @method datetime getProjectUptime
 * @method int getProjectVersion
 * @method int getRegionNum
 * @method int getMergedTypeId
 * @method string getDescription
 * @method string getPlace
 * @method int getComplaintStatus
 * @method int getUIKNum
 * @method int getTIKNum
 * @method string getMedia
 * @method int getObsrole
 * @method int getImpact
 * @method datetime getObstime
 * @method int getLoadtime
 * @method int getRecchanel
 * @method string getHqcomment
 * @method int getObsid
 * @method int getObstrusted
 * @method int getPoliceReaction
 * @method int getRectified
 *
 * @method ParserIKData_Model_Violation setProjectId
 * @method ParserIKData_Model_Violation setProjectCode
 * @method ParserIKData_Model_Violation setProjectUptime
 * @method ParserIKData_Model_Violation setProjectVersion
 * @method ParserIKData_Model_Violation setRegionNum
 * @method ParserIKData_Model_Violation setMergedTypeId
 * @method ParserIKData_Model_Violation setDescription
 * @method ParserIKData_Model_Violation setPlace
 * @method ParserIKData_Model_Violation setComplaintStatus
 * @method ParserIKData_Model_Violation setUIKNum
 * @method ParserIKData_Model_Violation setTIKNum
 * @method ParserIKData_Model_Violation setMedia
 * @method ParserIKData_Model_Violation setObsrole
 * @method ParserIKData_Model_Violation setImpact
 * @method ParserIKData_Model_Violation setObstime
 * @method ParserIKData_Model_Violation setLoadtime
 * @method ParserIKData_Model_Violation setRecchanel
 * @method ParserIKData_Model_Violation setHqcomment
 * @method ParserIKData_Model_Violation setObsid
 * @method ParserIKData_Model_Violation setObstrusted
 * @method ParserIKData_Model_Violation setPoliceReaction
 * @method ParserIKData_Model_Violation setRectified
 *
 * @author admin
 */
class ParserIKData_Model_Violation extends ParserIKData_Model
{
    private static $_channels = array(
        '1' => 'phone',
        '2' => 'sms',
        '3' => 'web',
        '4' => 'email',
        '5' => 'person',
    );

    private static $_policeReaction = array(
            '1' => 'notified',
            '2' => 'arrived',
    );

    private $_media = array();

    public static function channelNameByCode($code) {
        return isset(self::$_channels[$code]) ? self::$_channels[$code] : null;
    }

    public static function channelCodeByName($name) {
        $val = array_keys(self::$_channels, $name);
        return (count($val) == 0 ? null : current($val));
    }

    public static function policeReactionNameByCode($code) {
        return isset(self::$_policeReaction[$code]) ? self::$_policeReaction[$code] : null;
    }

    public static function policeReactionCodeByName($name) {
        $val = array_keys(self::$_policeReaction, $name);
        return (count($val) == 0 ? null : current($val));
    }

    public static function create()
    {
        return new self();
    }

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->getProjectId() . '-' . $this->getProjectCode();
    }

    /**
     * @param string $type
     * @param string $url
     * @param string $description
     * @return ParserIKData_Model_Violation
     */
    public function addMedia($type, $url, $description)
    {
        $this->_media[] = array('type' => $type, 'url' => $url, 'description' => $description);
        return $this;
    }

    /**
     * hash, однозначно определяющий данные
     * @return string
     */
    public function getDataHash()
    {
        $data = $this->toStringArray();
        $skippedIndices = array(2, 3, 15, 16, 19, 22);
        foreach ($skippedIndices as $ind) {
            unset($data[$ind]);
        }
        return md5(serialize($data));
    }

    public function getMediaAsArray()
    {
        return $this->_media;
    }

    public function getMedia()
    {
        return serialize($this->getMediaAsArray());
    }

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getProjectId(); // 0
        $data[] = $this->getProjectCode(); // 1
        $data[] = $this->getProjectUptime(); // 2
        $data[] = $this->getProjectVersion(); // 3
        $data[] = $this->getRegionNum(); // 4
        $data[] = $this->getMergedTypeId(); // 5
        $data[] = $this->getDescription(); // 6
        $data[] = $this->getPlace(); // 7
        $data[] = $this->getComplaintStatus(); // 8
        $data[] = $this->getUIKNum(); // 9
        $data[] = $this->getTIKNum(); // 10
        $data[] = $this->getMedia(); // 11
        $data[] = $this->getObsrole(); // 12
        $data[] = $this->getImpact(); // 13
        $data[] = $this->getObstime(); // 14
        $data[] = $this->getLoadtime(); // 15
        $data[] = $this->getRecchanel(); // 16
        $data[] = $this->getHqcomment(); // 17
        $data[] = $this->getObsid(); // 18
        $data[] = $this->getObstrusted(); // 19
        $data[] = $this->getPoliceReaction(); // 20
        $data[] = $this->getRectified(); // 21
        $data[] = $this->getRectime(); // 22
        return $data;
    }

    /**
     * @return string[]
     */
    public function toStringArray()
    {
        $data = $this->toArray();
        foreach($data as $k => $v) {
            $data[$k] = strval($v);
        }
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_ViolationType
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['ProjectId']        = $array[0];
        $data['ProjectCode']      = $array[1];
        $data['ProjectUptime']    = $array[2];
        $data['ProjectVersion']   = $array[3];
        $data['RegionNum']        = $array[4];
        $data['MergedTypeId']     = $array[5];
        $data['Description']      = $array[6];
        $data['Place']            = $array[7];
        $data['ComplaintStatus']  = $array[8];
        $data['UIKNum']           = $array[9];
        $data['TIKNum']           = $array[10];
        $data['Media']            = $array[11];
        $data['Obsrole']          = $array[12];
        $data['Impact']           = $array[13];
        $data['Obstime']          = $array[14];
        $data['Loadtime']         = $array[15];
        $data['Recchanel']        = $array[16];
        $data['Hqcomment']        = $array[17];
        $data['Obsid']            = $array[18];
        $data['Obstrusted']       = $array[19];
        $data['PoliceReaction']   = $array[20];
        $data['Rectified']        = $array[21];
        $data['Rectime']          = $array[22];

        $viol = parent::fromArray($data);
        $viol->_media = unserialize($viol->_properties['Media']);
        return $viol;
    }
}