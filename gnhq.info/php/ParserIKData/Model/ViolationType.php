<?php
/**
 * @method int getMergedType
 * @method int getProjectType
 * @method string getProjectCode
 * @method string getSeverity
 * @method string getGroup
 * @method ParserIKData_Model_ViolationType setMergedType
 * @method ParserIKData_Model_ViolationType setProjectType
 * @method ParserIKData_Model_ViolationType setProjectCode
 * @method ParserIKData_Model_ViolationType setSeverity
 * @method ParserIKData_Model_ViolationType setGroup
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
        $data[] = $this->getGroup();
        $data[] = $this->getSeverity();
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_ViolationType
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['MergedType']   = $array[0];
        $data['ProjectType']  = $array[1];
        $data['ProjectCode']  = $array[2];
        $data['FullName']     = $array[3];
        $data['Group']        = $array[4];
        $data['Severity']     = $array[5];
        return parent::fromArray($data);
    }
}