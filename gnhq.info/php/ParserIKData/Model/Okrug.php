<?php
/**
 * @method string getTikDataLink
 * @method int getRegionNum
 * @method ParserIKData_Model_Okrug setTikDataLink
 * @method ParserIKData_Model_Okrug setRegionNum
 * @author admin
 */
class ParserIKData_Model_Okrug extends ParserIKData_Model
{
    private $_discrepancyCount = 0;

    /**
     * @return number
     */
    public function getDiscrepancyCount()
    {
        return $this->_discrepancyCount;
    }

    /**
     * @param int $cnt
     * @return ParserIKData_Model_Okrug
     */
    public function setDiscrepancyCount($cnt)
    {
        $this->_discrepancyCount = intval($cnt);
        return $this;
    }

    /**
     * @var ParserIKData_Model_TIK[]
     */
    private $_tiks = array();


    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::getUniqueId()
     */
    public function getUniqueId()
    {
        return $this->getAbbr();
    }


    /**
     * аббривеатура
     * @return string
     */
    public function getAbbr()
    {
        return self::_normalizeName($this->getFullName());
    }

    /**
     * @param ParserIKData_Model_TIK $tik
     * @return ParserIKData_Model_Okrug
     */
    public function addTik($tik)
    {
        $tik->_friendSetOkrug($this);
        $this->_tiks[$tik->getUniqueId()] = $tik;
        return $this;
    }

    /**
     * @return ParserIKData_Model_TIK[]
     */
    public function getTiks()
    {
        return $this->_tiks;
    }


    /**
     * @param string $modifiedName
     * @param string[] $ignoreStrings
     * @return ParserIKData_Model_Okrug|null
     */
    public static function findByModifiedName($modifiedName, $ignoreStrings = array())
    {
        $name = self::_normalizeName($modifiedName, $ignoreStrings);
        return self::getFromPool($name);
    }



    /**
     * @param string $name
     * @param string[] $ignoreStrings
     * @return string
     */
    private static function _normalizeName($name, $ignoreStrings = array())
    {
        if (mb_strpos($name, 'еленог') > 0) {
            return 'ЗелАО';
        } else {
            foreach ($ignoreStrings as $ignoreString) {
                $name = str_replace($ignoreString, '', $name);
            }
            $name = trim($name);

            $parts = array();
            $words = explode(' ', $name);

            foreach ($words as $word) {
                $parts = array_merge($parts, explode('-', $word));
            }
            $abbr = '';
            foreach($parts as $part) {
                $abbr .= mb_strtoupper(mb_substr($part, 0, 1, mb_detect_encoding($part)), mb_detect_encoding($part));
            }
            return $abbr;
        }
    }

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getAbbr();
        $data[] = $this->getFullName();
        $data[] = $this->getLink();
        $data[] = $this->getTikDataLink();
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @return ParserIKData_Model_Okrug
     */
    public static function fromArray($array)
    {
        $data = array();
        $data['FullName']    = $array[1];
        $data['Link']        = $array[2];
        $data['TikDataLink'] = $array[3];
        return parent::fromArray($data);
    }
}