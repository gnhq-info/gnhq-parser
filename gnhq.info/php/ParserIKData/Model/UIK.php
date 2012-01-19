<?php
/**
* @author admin
* @method string getTikUniqueId
* @method ParserIKData_Model_UIK setTikUniqueId
*/
class ParserIKData_Model_UIK extends ParserIKData_Model
{
    /**
    * @return ParserIKData_Model_TIK|null
    */
    public function getTik()
    {
        return ParserIKData_Model_TIK::getFromPool($this->getTikUniqueId());
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
}