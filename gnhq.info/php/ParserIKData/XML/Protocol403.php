<?php
class ParserIKData_XMLProcessor_Protocol403 extends ParserIKData_XMLProcessor_Abstract
{
    /**
     * @param ParserIKData_Model_Protocol403 $newProto
     * @param ParserIKData_Model_Protocol403 $currentProto
     * @return string
     */
    public function updateIfNecessary($newProto, $currentProto)
    {
        // новое нарушение
        if ($currentProto === null) {
            $this->_getProtocolGateway()->insert($newProto);
            return 'inserted';
        }

        // версии совпадают, но у нас свежее время обновления (в т.ч. для проектов, не использующих версии) - не обновляем
        if ( strtotime($currentProto->getUpdateTime()) > strtotime($newProto->getUpdateTime()) ) {
            return 'skipped time';
        }

        $this->_getProtocolGateway()->update($newProto);
        return 'updated';
    }

    /**
     * @param string $xml
     * @param string $projectCode
     * @return ParserIKData_Model_Protocol403|string
     */
    public function createFromXml($sXml, $projectCode)
    {
        $errors = array();
        if (!$sXml instanceof SimpleXMLElement) {
            $sXml = simplexml_load_string($sXml);
        }
        $proto = ParserIKData_Model_Protocol403::create();

        // обязательные поля
        $proto->setResultType($projectCode);
        // id in project
        if (!$sXml->id) {
            $errors[] = 'Не указан id';
        } else {
            $proto->setProjectId($this->_filterString((string)$sXml->id, 50));
        }

        // update time
        if (!$sXml->updt) {
            $errors[] = 'Не указано время обновления';
        } else {
            $proto->setUpdateTime($this->_prepareTime((string)$sXml->updt));
        }

        // uik full
        if(!$sXml->region) {
            $errors[] = 'Не указан регион';
        } else {
            $regionNum = (int)$sXml->region;
        }
        if(!$sXml->uik) {
            $errors[] = 'Не указан номер УИК';
        } else {
            $uikNum = (int)$sXml->uik;
        }
        $proto->setIkFullName($regionNum * ParserIKData_Model_UIK::UIKMODULE + $uikNum);

        // lines
        $mandatoryIndices = array(9, 10, 19, 20, 21, 22, 23);
        $lineData = array();
        for ($i = 1; $i < ParserIKData_Model_Protocol403::LINE_AMOUNT; $i++) {
            $prtName = 'p' . $i;
            if ($sXml->children()->$prtName) {
                $lineData[$i] = (int)$sXml->children()->$prtName;
            } else {
                if (in_array($i, $mandatoryIndices)) {
                    $errors[] = 'Не указано обязательное поле протокола '.$i;
                } else {
                    $lineData[$i] = 0;
                }
            }
        }
        $proto->setData($lineData);


        // необязательные поля с дефолтными значениями
        // complaint amount
        if ($sXml->ncomp) {
            $proto->setClaimCount((int)$sXml->ncomp);
        } else {
            $proto->setClaimCount(0);
        }

        // returning
        if (!empty($errors)) {
            return implode(', ' , $errors);
        } else {
            return $proto;
        }
    }


    private function _getUikRGateway()
    {
        return new ParserIKData_Gateway_UIKRussia();
    }


    private function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol403();
    }
}