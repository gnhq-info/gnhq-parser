<?php
include_once('Model/Region.php');
include_once('Model/Okrug.php');
include_once('Model/TIK.php');
include_once('Model/TIKRussia.php');
include_once('Model/UIK.php');
include_once('Model/UIKRussia.php');
include_once('Model/Protocol412.php');
include_once('Model/Report412.php');
include_once('Model/Watch412.php');
include_once('Model/ViolationType.php');
include_once('Model/Violation.php');
include_once('Model/Twit.php');

/**
 * @method string getFullName
 * @method string getLink
 *
 * @method ParserIKData_Model setFullName
 * @method ParserIKData_Model setLink
 * @author admin
 *
 */
abstract class ParserIKData_Model
{
    protected $_properties = array();

    protected static $_pool = array();

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return md5($this->getFullName());
    }

    /**
     * @param string $name
     * @param array$arguments
     * @throws Exception
     * @return Ambigous <NULL, multitype:>|ParserIKData_Model
     */
    public function __call($name, $arguments)
    {
        $param = substr($name, 3);
        switch (substr($name, 0, 3)) {
            case 'get':
                return array_key_exists($param, $this->_properties) ? $this->_properties[$param] : null;
                break;

            case 'set':
                if (0 == count($arguments)) {
                    throw new Exception("You must set parameter");
                }
                $this->_properties[$param] = $arguments[0];
                // для соответствия при сравнения
                ksort($this->_properties);
                return $this;
                break;

            default:
                throw new Exception("Wrong method name ".$name);
        }
    }

    /**
     * @param array $params
     * @return ParserIKData_Model
     */
    protected function _setWebPageData($params)
    {
        return $this;
    }

    /**
     * @param string $fullName
     * @param string $link
     * @param array $extraOparams
     * @return ParserIKData_Model
     */
    public static function createFromPageInfo($fullName, $link, $extraParams)
    {
        $className = get_called_class();
        $item = new $className();
        if (!$item instanceof ParserIKData_Model) {
            throw new Exception('Bad classname: '.$className);
        }

        $item
            ->setFullName($fullName)
            ->setLink($link)
            ->_setWebPageData($extraParams);

        return self::_addToPool($item);
    }


    /**
     * @param ParserIKData_Model $item
     * @return ParserIKData_Model
     */
    protected static function _addToPool($item)
    {
        self::$_pool[get_class($item)][$item->getUniqueId()] = $item;
        return $item;
    }

    /**
    * @param string $name
    * @return string
    */
    protected static function _calcNormalizedHash($name)
    {
        return md5($name);
    }

    /**
     * @param string $uniqueId
     * @return ParserIKData_Model
     */
    public static function getFromPool($uniqueId)
    {
        $className = get_called_class();
        if (!empty(self::$_pool[$className][$uniqueId])) {
            return self::$_pool[$className][$uniqueId];
        } else {
            return null;
        }
    }

    /**
     * @return ParserIKData_Model[]
     */
    public static function getAllOBjects()
    {
        $className = get_called_class();
        return isset(self::$_pool[$className]) ? self::$_pool[$className] : array() ;
    }

    /**
     * @return array()
     */
    public function getParams()
    {
        return $this->_properties;
    }

    /**
     * @return array()
     */
    public function toArray()
    {
        return $this->getParams();
    }


    /**
     * @param array $array
     * @return ParserIKData_Model
     */
    public static function fromArray($array)
    {
        $className = get_called_class();
        $item = new $className;
        $item->_properties = $array;
        self::_addToPool($item);
        return $item;
    }


    final protected function __construct()
    {

    }
}