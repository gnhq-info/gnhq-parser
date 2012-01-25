<?php
/**
 * @method array getData()        'результаты'
 *
 * @method ParserIKData_Model_Protocol412 setData()
 *
 * protocol (resultaty) vyborov po komissii
 * @author admin
 */
class ParserIKData_Model_Protocol412 extends ParserIKData_Model
{
    const TYPE_GN    = 'GN';
    const TYPE_OF    = 'OF';
    const IkTYPE_UIK = 'UIK';
    const IkTYPE_TIK = 'TIK';
    const IkTYPE_OIK = 'OIK';

    public function getUniqueId()
    {
        return $this->getFullName() . ':' . $this->getIkType() . ':' . $this->getType();
    }

    public function isTypeGn()
    {
        return $this->_properties['Type'] == self::TYPE_GN;
    }

    public function isTypeOf()
    {
        return $this->_properties['Type'] == self::TYPE_OF;
    }

    public function setTypeGn()
    {
        $this->_properties['Type'] = self::TYPE_GN;
        return $this;
    }

    public function setTypeOf()
    {
        $this->_properties['Type'] = self::TYPE_OF;
        return $this;
    }

    public function setIkTypeUIK()
    {
        $this->_properties['IkType'] = self::IkTYPE_UIK;
    }

    public function setIkTypeTIK()
    {
        $this->_properties['IkType'] = self::IkTYPE_TIK;
    }

    public function setIkTypeOIK()
    {
        $this->_properties['IkType'] = self::IkTYPE_OIK;
    }

    /**
     * (non-PHPdoc)
     * @see ParserIKData_Model::toArray()
     */
    public function toArray()
    {
        $data = array();
        $data[] = $this->getFullName();
        $data[] = $this->getIkType();
        $data[] = $this->getType();
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
        $data['FullName'] = array_shift($arr);
        $data['IkType']   = array_shift($arr);
        $data['Type']     = array_shift($arr);
        $data['Data']     = $arr;
        $data['Link']     = '';
        $item = parent::fromArray($data);
        return $item;
    }
}