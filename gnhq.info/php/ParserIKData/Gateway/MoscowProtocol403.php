<?php
/**
 * @author admin
 */
class ParserIKData_Gateway_MoscowProtocol403 extends ParserIKData_Gateway_Abstract
{
    protected $_table = 'moscow_municipal_result';
    protected $_modelClass = 'ParserIKData_Model_MoscowProtocol403';


    /**
    * @param ParserIKData_Gateway_MoscowProtocol403 $proto
    */
    public function insert($proto)
    {
        $this->_getDriver()->query($this->_insertQuery($proto, $this->_table));
    }



    /**
    * @param ParserIKData_Model_MoscowProtocol403 $proto
    * @param string $table
    * @return string
    */
    protected function _insertQuery($proto, $table)
    {
        $lineFields = array();
        for ($i = 1; $i <= ParserIKData_Model_MoscowProtocol403::LINE_AMOUNT; $i++) {
            $lineFields[] = 'Line' . $i;
        }
        $lineFields = implode(', ', $lineFields);

        $lineData = array();
        for ($i = 1; $i <= ParserIKData_Model_MoscowProtocol403::LINE_AMOUNT; $i++) {
            $lineData[] = intval($proto->getProtocolValue($i));
        }
        $lineData = implode(', ', $lineData);

        $query = sprintf('insert into '.$table.' (OkrId, UikNum, %s)
              values (%d, %d,  %s)',
              	$lineFields,
                $proto->getOkrId(),
                $proto->getUikNum(),
                $lineData
            );
        return $query;
    }

}