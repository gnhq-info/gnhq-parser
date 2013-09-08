<?php
class ParserIKData_XMLProcessor_Violation extends ParserIKData_XMLProcessor_Abstract
{
    protected $_projectCode = null;

    protected $_updateData = array();

    protected $_uikRGateway = null;

    protected $_vtypeGateway = null;

    public function __construct($projectCode)
    {
        $this->_projectCode = $projectCode;
        $current = $this->_getViolationGateway()->getForProject($projectCode);
        /* @var $viol ParserIKData_Model_Violation */
        foreach ($current as $viol) {
            $this->_violToUpdateData($viol);
        }
    }

    /**
     * @param string $src
     * @return SimpleXMLElement
     */
    public function loadFromSource($src)
    {
        return simplexml_load_file($src);
    }

    /**
     * @return stdClass|false
     */
    protected function _loadFromJsonSource($src)
    {
        $content = file_get_contents($src);
        if (!$content) {
            return false;
        }
        return json_decode($content, false);
    }

    /**
     * @param ParserIKData_Model_Violation $newViol
     * @return string
     */
    public function updateIfNecessary($newViol)
    {
        $ind = $newViol->getProjectId();
        // новое нарушение
        if (empty($this->_updateData[$ind])) {
            $this->_getViolationGateway()->insert($newViol);
            $this->_violToUpdateData($newViol);
            return 'inserted';
        }


        // у нас более свежая версия - не обновляем
        if ($this->_updateData[$ind]['version'] > $newViol->getProjectVersion()) {
            return 'skipped version';
        }

        // версии совпадают, но у нас свежЕе время обновления (в т.ч. для проектов, не использующих версии) - не обновляем
        if ($this->_updateData[$ind]['version'] == $newViol->getProjectVersion() && $this->_updateData[$ind]['time'] >= strtotime($newViol->getProjectUptime()) ) {
            return 'skipped time';
        }

        // не изменились актуальные данные
        if ($this->_updateData[$ind]['hash'] == $newViol->getDataHash()) {
            return 'skipped hash';
        }

        $this->_getViolationGateway()->update($newViol);
        $this->_violToUpdateData($newViol);
        return 'updated';
    }

    /**
     * @param string|SimpleXMLElement $xml
     * @return ParserIKData_Model_Violation|string
     */
    public function createFromXml($xml)
    {
        $errors = array();
        if (!$xml instanceof SimpleXMLElement) {
            $sXml = simplexml_load_string($xml);
        } else {
            $sXml = $xml;
        }
        if (!$sXml instanceof SimpleXMLElement) {
            return 'Bad simple xml';
        }

        if ($this->_skipFromRetranslator($sXml)) {
            return 'skipped - not current project';
        }

        $viol = ParserIKData_Model_Violation::create();

        // обязательные поля
        $viol->setProjectCode($this->_projectCode);
        // id in project
        if (!$sXml->id) {
            $errors[] = 'Не указан id';
        } else {
            $viol->setProjectId($this->_filterString((string)$sXml->id, 50));
        }

        // update time
        if (!$sXml->updt) {
            $errors[] = 'Не указано время обновления';
        } else {
            $viol->setProjectUptime($this->_prepareTime((string)$sXml->updt));
        }

        // region
        if(!$sXml->region) {
            $errors[] = 'Не указан регион';
        } else {
            $viol->setRegionNum((int)$sXml->region);
        }
        // тип нарушения
        if (!$sXml->type) {
            $errors[] = 'Нет типа нарушения';
        } else {
            $viol->setMergedTypeId($this->_getMergedType($this->_projectCode, (string)$sXml->type));
        }
        // description
        if (!$sXml->obscomment) {
            $viol->setDescription('');
        } else {
            $viol->setDescription($this->_filterString((string)$sXml->obscomment));
        }


        // необязательные поля с дефолтными значениями
        // complaint status
        if ($sXml->complaint) {
            $viol->setComplaintStatus($this->_filterString((string)$sXml->complaint, 1));
        } else {
            $viol->setComplaintStatus('n');
        }
        // obsrole
        if ($sXml->obsrole) {
            $viol->setObsrole($this->_filterString((string)$sXml->obsrole, 1));
        } else {
            $viol->setObsrole('n');
        }
        // version of the data
        if ($sXml->version) {
            $version = (int)$sXml->version;
        } else {
            $version = 0;
        }
        $viol->setProjectVersion($version);


        // необязательные поля без дефолтных значений
        if ($sXml->place) {
            $viol->setPlace($this->_filterString((string)$sXml->place, 100));
        }
        if ($sXml->impact) {
            $viol->setImpact((int)$sXml->impact);
        }
        if ($sXml->obstime) {
            $viol->setObstime($this->_prepareTime((string)$sXml->obstime));
        } else {
            $viol->setObstime($this->_prepareTime((string)$sXml->updt));
        }
        if ($sXml->recchannel) {
            $channel = (string)$sXml->recchannel;
            if ($this->_prepareChannel($channel)) {
                $viol->setRecchanel($this->_prepareChannel($channel));
            }
        }
        if ($sXml->hqcomment) {
            $viol->setHqcomment($this->_filterString((string)$sXml->hqcomment));
        }
        if ($sXml->obsid) {
            $viol->setObsid($this->_filterString((string)$sXml->obsid, 50));
        }
        if ($sXml->obstrusted) {
            $viol->setObstrusted((int)$sXml->obstrusted);
        }
        if ($sXml->police) {
            $police = (string)$sXml->police;
            if ($this->_preparePoliceReaction($police) !== null) {
                $viol->setPoliceReaction($this->_preparePoliceReaction($police));
            }
        }
        if ($sXml->rectified) {
            $rectified = (string)$sXml->rectified == '1' ? 1 : 0;
            $viol->setRectified($rectified);
        }

        if ($sXml->rectime) {
            $viol->setRectime($this->_prepareTime((string)$sXml->rectime));
        }

        if ($sXml->media) {
            foreach ($sXml->media as $mXml) {
                $mediaType  = $this->_filterString((string)$mXml->Attributes()->type, 10);
                $mediaUrl   = $this->_filterString((string)$mXml->Children()->url);
                $mediaDescr = $this->_filterString((string)$mXml->Children()->descr);
                $viol->addMedia($mediaType, $mediaUrl, $mediaDescr);
            }
        }

        // привязка к ИК
        $viol->setUIKNum(0);
        $viol->setTIKNum(0);
        if ($sXml->stationtype && $sXml->uik) {
            if ((string)$sXml->stationtype == '1' || (string)$sXml->stationtype == 'UIK') {
                if (is_numeric((string)$sXml->uik)) {
                    $viol->setUIKNum((int)$sXml->uik);
                    $viol->setTIKNum($this->_getUikRGateway()->findTikNumByRegionAndUik($viol->getRegionNum(), $viol->getUIKNum()));
                } else {
                    $viol->setPlace($this->_filterString((string)$sXml->uik, 50));
                }
            } elseif ((string)$sXml->stationtype == '2' || (string)$sXml->stationtype == 'TIK' ) {
                if (is_numeric((string)$sXml->uik)) {
                    $viol->setTIKNum((int)$sXml->uik);
                } else {
                    $viol->setPlace($this->_filterString((string)$sXml->uik, 50));
                }
            }
        }

        // returning
        if (!empty($errors)) {
            return implode(', ' , $errors);
        } else {
            return $viol;
        }
    }

    /**
     * @param stdClass $dataObj
     * @return ParserIKData_Model_Violation|string
     */
    protected function _createFromStdClass($dataObj, $regionNum)
    {
        $errors = array();

        if (!$dataObj instanceof stdClass) {
            return 'Bad data';
        }

        $viol = ParserIKData_Model_Violation::create();

        // обязательные поля
        $viol->setProjectCode($this->_projectCode);
        // id in project
        if (!empty($dataObj->id)) {
            $viol->setProjectId($this->_filterString((string)$dataObj->id, 50));
        } elseif($dataObj->{'id='}) {
            $viol->setProjectId($this->_filterString((string)$dataObj->{'id='}, 50));
        } else {
            $errors[] = 'Не указан id';
        }

        // update time
        if (!$dataObj->created_at) {
            $errors[] = 'Не указано время обновления';
        } else {
            $viol->setProjectUptime($this->_prepareTime((string)$dataObj->created_at));
        }

        // region
        $viol->setRegionNum($regionNum);

        // тип нарушения
        if (!$dataObj->violation_type_id) {
            $viol->setMergedTypeId(0);
        } else {
            $viol->setMergedTypeId($this->_getMergedType($this->_projectCode, (string)$dataObj->violation_type_id));
        }
        // description
        if (!$dataObj->text) {
            $errors[] = 'Нет описания';
        } else {
            $viol->setDescription($this->_filterString((string)$dataObj->text));
        }

        // необязательные поля с дефолтными значениями
        // complaint status
        $viol->setComplaintStatus('n');
        // obsrole
        $viol->setObsrole('n');
        // version of the data
        $viol->setProjectVersion(0);

        $viol->setObstime($this->_prepareTime((string)$dataObj->created_at));

        // defaults - for correct hash mapping
        $viol
            ->setObsrole(0)
            ->setRectified(0)
            ->setPoliceReaction(0)
            ->setImpact(0);

        // привязка к ИК
        $viol->setUIKNum(0);
        $viol->setTIKNum(0);
        if ($whereText = (string)$dataObj->uic) {
            if (!mb_strstr($whereText, ' ')) {
                $placeText = $whereText;
            } else {
                list($whereType, $whereNum) = explode(' ', $whereText);
                if (is_numeric($whereNum)) {
                    switch (mb_strtoupper($whereType)) {
                        case 'УИК':
                            $uikNum = intval($whereNum);
                            $tikNum = $this->_getUikRGateway()->findTikNumByRegionAndUik($viol->getRegionNum(), $uikNum);
                            break;

                        case 'ТИК':
                            $tikNum = intval($whereNum);
                            break;

                        default:
                            $placeText = $whereText;
                            break;
                    }
                } else {
                    $placeText = $whereText;
                }
            }

            if (isset($placeText)) {
                $viol->setPlace($placeText);
            }
            if (isset($uikNum)) {
                $viol->setUIKNum($uikNum);
            }
            if (isset($tikNum)) {
                $viol->setTIKNum($tikNum);
            }
        }

        // returning
        if (!empty($errors)) {
            return implode(', ' , $errors);
        } else {
            return $viol;
        }
    }

    protected function _getMergedType($projectCode, $projectType)
    {
        return $this->_getTypeGateway()->findMergedTypeByProjectType($projectCode, $projectType);
    }


    /**
     * @return ParserIKData_Gateway_UIKRussia
     */
    protected function _getUikRGateway()
    {
        if ($this->_uikRGateway === null) {
            $this->_uikRGateway = new ParserIKData_Gateway_UIKRussia();
            $this->_uikRGateway->setUseCache(true);
        }
        return $this->_uikRGateway;
    }

    /**
     * @return ParserIKData_Gateway_ViolationType
     */
    private function _getTypeGateway()
    {
        if ($this->_vtypeGateway === null) {
            $this->_vtypeGateway = new ParserIKData_Gateway_ViolationType();
            $this->_vtypeGateway->setUseCache(true);
        }
        return $this->_vtypeGateway;
    }

    protected function _getViolationGateway()
    {
        return new ParserIKData_Gateway_Violation();
    }

    private function _prepareChannel($channel)
    {
        if (is_numeric($channel)) {
            if (ParserIKData_Model_Violation::channelNameByCode($channel)) {
                return $channel;
            }
        } else {
            if (ParserIKData_Model_Violation::channelCodeByName(mb_strtolower($channel))) {
                return ParserIKData_Model_Violation::channelCodeByName(mb_strtolower($channel));
            }
        }
        return null;
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
        return false;
    }

    private function _preparePoliceReaction($pr)
    {
        if (is_numeric($pr)) {
            if (ParserIKData_Model_Violation::policeReactionNameByCode($pr)) {
                return $pr;
            } else {
                return 0;
            }
        } else {
            if (ParserIKData_Model_Violation::policeReactionCodeByName(mb_strtolower($pr))) {
                return ParserIKData_Model_Violation::policeReactionCodeByName(mb_strtolower($pr));
            }
        }
        return null;
    }

    /**
     * @param ParserIKData_Model_Violation $viol
     */
    protected function _violToUpdateData($viol)
    {
        $this->_updateData[$viol->getProjectId()] = array (
            	'time'    => strtotime($viol->getProjectUptime()),
            	'version' => $viol->getProjectVersion(),
            	'hash'    => $viol->getDataHash()
        );
    }
}