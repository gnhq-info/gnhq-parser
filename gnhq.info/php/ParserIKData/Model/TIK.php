<?php
/**
 * @author admin
 * @method string getOkrugUniqueId
 * @method array  getMembers  // члены ТИК с правом решающего голоса
 * @method string getChief
 * @method string getDeputy
 * @method string getSecretary
 * @method string getAddress
 * @method string getPhone
 * @method string getSelfInfoLink
 * @method string getSostavLink
 * @method string getAddressLink
 *
 * @method ParserIKData_Model_TIK setOkrugUniqueId
 * @method ParserIKData_Model_TIK setMembers
 * @method ParserIKData_Model_TIK setChief
 * @method ParserIKData_Model_TIK setDeputy
 * @method ParserIKData_Model_TIK setSecretary
 * @method ParserIKData_Model_TIK setAddress
 * @method ParserIKData_Model_TIK setPhone
 * @method ParserIKData_Model_TIK setSelfInfoLink
 * @method ParserIKData_Model_TIK setSostavLink
 * @method ParserIKData_Model_TIK setAddressLink
 *
 */
class ParserIKData_Model_TIK extends ParserIKData_Model
{
    const MEMBERS_SEPARATOR = ', ';

    /**
    * @var ParserIKData_Model_UIK[]
    */
    private $_uiks = array();


    public function getUniqueId()
    {
        return self::_calcNormalizedHash($this->getShortName());
    }

    /**
     * @param ParserIKData_Model_UIK $uik
     * @return ParserIKData_Model_TIK
     */
    public function addUik($uik)
    {
        $this->_uiks[$uik->getUniqueId()] = $uik;
        return $this;
    }

    /**
     * @return ParserIKData_Model_UIK[]
     */
    public function getUiks()
    {
        return $this->_uiks;
    }

    /**
     * @return ParserIKData_Model_Okrug|null
     */
    public function getOkrug()
    {
        return ParserIKData_Model_Okrug::getFromPool($this->getOkrugUniqueId());
    }

    /**
     * only for calling from ParserIKData_Model_Okrug->addTik
     * @param ParserIKData_Model_Okrug $okrug
     * @return ParserIKData_Model_TIK
     */
    public function _friendSetOkrug(ParserIKData_Model_Okrug $okrug)
    {
        return $this->setOkrugUniqueId($okrug->getUniqueId());
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return self::_normalizeName($this->getFullName());
    }


    /**
    * @param string $modifiedName
    * @param string[] $ignoreStrings
    * @return ParserIKData_Model_Okrug|null
    */
    public static function findByModifiedName($modifiedName, $ignoreStrings = array())
    {
        $name = self::_normalizeName($modifiedName, $ignoreStrings);
        $hash = self::_calcNormalizedHash($name);
        return self::getFromPool($hash);
    }

    /**
     * @param string $name
     * @return string
     */
    public static function _normalizeName($name)
    {
        $toBlanks = array(
            'Территориальная избирательная комиссия',
            'района',
            'район',
        );
        foreach($toBlanks as $toBlank) {
            $name = str_replace($toBlank, '', $name);
        }
        $name = trim($name);
        $name = mb_strtolower($name, mb_detect_encoding($name));
        $name = str_replace(chr(209).chr(129), chr(99), $name); // fix for Чертаново Северное

        /* to parts */
        $lenForUnification = 6;
        $name = str_replace('-', ' ', $name);
        $parts = explode(' ', $name);
        foreach ($parts as $k => $part) {
            if ($part == ' ') {
                unset($parts[$k]);
            }
        }
        sort($parts);
        foreach ($parts as $k => $part) {
            if ($parts == ' ') {
                unset ($parts[$k]);
            } else {
                $parts[$k] = trim(mb_substr($part, 0, $lenForUnification, mb_detect_encoding($part)));
            }
        }
        $name = implode(' ', $parts);

        $name = str_replace(chr(32).chr(32), '', $name); // fix for Хорошево-Мневники

        return $name;
    }

    public function toArray()
    {
        $data = array();
        $data[] = $this->getOkrugUniqueId();
        $data[] = $this->getFullName();
        $data[] = $this->getAddress();
        $data[] = $this->getPhone();
        $data[] = $this->getChief();
        $data[] = $this->getDeputy();
        $data[] = $this->getSecretary();
        $data[] = implode(self::MEMBERS_SEPARATOR, $this->getMembers());
        $data[] = $this->getSelfInfoLink();
        $data[] = $this->getAddressLink();
        $data[] = $this->getSostavLink();
        $data[] = $this->getLink();
        return $data;
    }

    public static function fromArray($arr)
    {
        $data = array();
        $data['OkrugUniqueId'] = $arr[0];
        $data['FullName']      = $arr[1];
        $data['Address']       = $arr[2];
        $data['Phone']         = $arr[3];
        $data['Chief']         = $arr[4];
        $data['Deputy']        = $arr[5];
        $data['Secretary']     = $arr[6];
        $data['Members']       = explode(self::MEMBERS_SEPARATOR, $arr[7]);
        $data['SelfInfoLink']  = $arr[8];
        $data['AddressLink']   = $arr[9];
        $data['SostavLink']    = $arr[10];
        $data['Link']          = $arr[11];
        $item = parent::fromArray($data);
        /* @var $item ParserIKData_Model_TIK*/
        $item->getOkrug()->addTik($item);
        return $item;
    }

    public function DEBUG_PRINT()
    {
        print_r($this->getFullName(). PHP_EOL . $this->getSostavLink(). PHP_EOL);
        print_r($this->getChief(). PHP_EOL . $this->getDeputy(). PHP_EOL . $this->getSecretary(). PHP_EOL);
        print_r($this->getMembers());
        print_r(PHP_EOL . str_repeat('-', 20) . PHP_EOL);
    }
}