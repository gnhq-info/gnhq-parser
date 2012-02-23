<?php
/**
 * @method int getMergedType
 * @method int getProjectType
 * @method string getProjectCode
 * @method ParserIKData_Model_ViolationType setMergedType
 * @method ParserIKData_Model_ViolationType setProjectType
 * @method ParserIKData_Model_ViolationType setProjectCode
 * @author admin
 */
class ParserIKData_Model_ViolationType extends ParserIKData_Model
{
    const DEFAULT_MTYPE = 0;

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->getProjectType() . '-' . $this->getProjectCode();
    }


    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getMergedType();
        $data[] = $this->getProjectType();
        $data[] = $this->getProjectCode();
        $data[] = $this->getFullName();
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_ViolationType
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['MergedType']        = $array[0];
        $data['ProjectType']       = $array[1];
        $data['ProjectCode']       = $array[2];
        $data['FullName']          = $array[3];
        return parent::fromArray($data);
    }
}