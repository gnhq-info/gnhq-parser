<?php
class ParserIKData_XMLProcessor_Protocol403 extends ParserIKData_XMLProcessor_Abstract
{
    private $_projectCode;

    private $_updateData = array();

    public function __construct($projectCode)
    {
        $this->_projectCode = $projectCode;
        $current = $this->_getProtocolGateway()->findForProject($this->_projectCode);
        /* @var $proto ParserIKData_Model_Protocol403  */
        foreach ($current as $proto) {
            $this->_protoToUpdateData($proto);
        }
    }

    /**
     * @param ParserIKData_Model_Protocol403 $newProto
     * @return string
     */
    public function updateIfNecessary($newProto)
    {
        // новый протокол
        if (empty($this->_updateData[$newProto->getIkFullName()])) {
            $this->_getProtocolGateway()->insert($newProto);
            $this->_protoToUpdateData($newProto);
            return 'inserted';
        }

        // у нас свежее время обновления
        if ( $this->_updateData[$newProto->getIkFullName()]['time'] >= strtotime($newProto->getUpdateTime()) ) {
            // если не сопадают айдишники в рамках проекта - на всякий случай резервируем
            if ($this->_updateData[$newProto->getIkFullName()]['projectId'] != $newProto->getProjectId()) {
                $this->_getProtocolGateway()->reserve($newProto);
            }
            return 'skipped time';
        }

        $this->_getProtocolGateway()->update($newProto);
        $this->_protoToUpdateData($newProto);
        return 'updated';
    }

    /**
     * @param string|SimpleXMLElement $xml
     * @return ParserIKData_Model_Protocol403|string
     */
    public function createFromXml($sXml)
    {
        $errors = array();
        if (!$sXml instanceof SimpleXMLElement) {
            $sXml = simplexml_load_string($sXml);
        }

        if (!$sXml instanceof SimpleXMLElement) {
            return 'bad simple xml';
        }

        if ($this->_skipFromRetranslator($sXml)) {
            return 'skipped - not current project';
        }

        $proto = ParserIKData_Model_Protocol403::create();

        // обязательные поля
        $proto->setResultType($this->_projectCode);
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

    /**
     * в фиде-ретрансляторе могут быть данные от разных проектов (один фид <=> несколько проектов)
     * отбрасываем лишние
     * возвращает true для айтема, который нужно отбросить
     * @param SimpleXMLElement $sXml
     * @return boolean
     */
    private function _skipFromRetranslator($sXml)
    {
        switch ($this->_projectCode) {
            case PROJECT_SMS_EXITPOLE:
                if (!$sXml->children()->obsproject) {
                    return true;
                }
                if ((int)$sXml->children()->obsproject != 3) {
                    return true;
                }
                break;
            default:
                break;
        }
        return false;
    }

    /**
     * @return ParserIKData_Gateway_Protocol403
     */
    private function _getProtocolGateway()
    {
        return new ParserIKData_Gateway_Protocol403();
    }

    /**
     * @param ParserIKData_Model_Protocol403 $proto
     */
    private function _protoToUpdateData($proto)
    {
        $this->_updateData[$proto->getIkFullName()] = array (
        	'time' => strtotime($proto->getUpdateTime()),
        	'projectId' => $proto->getProjectId()
        );
    }
}