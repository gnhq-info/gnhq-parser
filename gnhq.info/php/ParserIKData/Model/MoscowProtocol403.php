<?php
/**
 * @method array getData()        'результаты'
 * @method int getUikNum()
 * @method int getOkrId()
 *
 * @method ParserIKData_Model_MoscowProtocol403 setData()
 * @method ParserIKData_Model_MoscowProtocol403 setUikNum()
 * @method ParserIKData_Model_MoscowProtocol403 setOkrId()
 *
 * @author admin
 */
class ParserIKData_Model_MoscowProtocol403 extends ParserIKData_Model
{
    const OFFSET = 11;

    const LINE_AMOUNT = 34;

    private $_uikCount = 1;

    public static function create()
    {
        return new self();
    }

    public function getUikCount()
    {
        return $this->_uikCount;
    }

    public function setUikCount($uikCount)
    {
        $this->_uikCount = $uikCount;
        return $this;
    }


    public function getUniqueId()
    {
        return $this->getUikNum();
    }
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getOkrId();
        $data[] = $this->getUikNum();
        $data = array_merge($data, $this->getData());
        return $data;
    }

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::fromArray()
     */
    public static function fromArray($arr)
    {
        $data = array();
        $data['UikNum'] = array_shift($arr);
        $data['OkrId']  = array_shift($arr);
        array_unshift($arr, '');
        unset($arr[0]);
        $data['Data']     = $arr;
        $item = parent::fromArray($data);
        return $item;
    }


    /**
    * @param int $index
    * @return int
    */
    public function getProtocolValue($index)
    {
        $data = $this->getData();
        if (isset ($data[$index] )) {
            return intval($data[$index]);
        } else {
            return 0;
        }
    }
}