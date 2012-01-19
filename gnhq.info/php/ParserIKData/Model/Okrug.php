<?php
/**
 * @method string getTikDataLink
 * @method ParserIKData_Model_Okrug setTikDataLink
 * @author admin
 */
class ParserIKData_Model_Okrug extends ParserIKData_Model
{
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
        return self::_calcNormalizedHash($this->getAbbr());
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
        $hash = self::_calcNormalizedHash($name);
        return self::getFromPool($hash);
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
        $data = parent::toArray();
        array_unshift($data, $this->getAbbr());
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