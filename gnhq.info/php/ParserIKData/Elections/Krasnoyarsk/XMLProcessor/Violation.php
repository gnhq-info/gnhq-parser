<?php
class ParserIKData_XMLProcessor_Violation_Krasnoyarsk extends ParserIKData_XMLProcessor_Violation
{
    public function loadFromSource($src)
    {
        if ($this->_projectCode = PROJECT_GOLOS) {
            $site = new ParserIKData_Site_KartaNarushenij();
            return $site->getViolationXml($src);
        } else {
            return parent::loadFromSource($src);
        }
    }

    protected function _getViolationGateway()
    {
        return new ParserIKData_Gateway_Violation_Krasnoyarsk();
    }


    /**
    * @param ParserIKData_Model_Violation $newViol
    * @return string
    */
    public function updateIfNecessary($newViol)
    {
        if ($this->_projectCode == PROJECT_GOLOS) {

            $ind = $newViol->getProjectId();
            // новое нарушение
            if (empty($this->_updateData[$ind])) {
                $this->_getViolationGateway()->insert($newViol);
                $this->_violToUpdateData($newViol);
                return 'inserted';
            }
            return 'skipped id';

        } else {

            return parent::updateIfNecessary($newViol);

        }
    }
}