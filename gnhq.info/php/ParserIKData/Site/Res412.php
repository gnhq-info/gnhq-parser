<?php
class ParserIKData_Site_Res412 extends ParserIKData_Site_Cik
{
    /**
     * (non-PHPdoc)
     * @see ParserIKData_Site_Abstract::_getConfigFileName()
     */
    protected function _getConfigFileName()
    {
        return 'res412.ini';
    }

    /**
     * array Okrug => Link
     * @return Ambigous <mixed, multitype:, multitype:Ambigous <Ambigous <string, false, boolean>> >
     */
    public function getOkrugLinks()
    {
        return $this->_getLowerLinks($this->_getCValue('rootPage'));
    }

    /**
     * @param string $link
     * @return Ambigous <Ambigous, mixed, multitype:, multitype:Ambigous <Ambigous <string, false, boolean>> >
     */
    public function getTIKLinks($link)
    {
        return $this->_getLowerLinks($link);
    }


    /**
     * @param string $link
     * @return Ambigous <string, false, boolean>
     */
    public function getResultTable($link)
    {
        $this->_getParser()->setPageSource($this->_getPageContent($link, $this->_getCValue('useCache')));
        $totalTable = $this->_getParser()->findMinContainingTag($this->_getCValue('totalTableIndicator'), 'table');
        $resultTable = $this->_getParser()->findMinContainingTag($this->_getCValue('resultTableIndicator'), 'table');
        return $resultTable;
    }




    /**
     * @param string $uikName
     * @param array $data
     * @return ParserIKData_Model_Protocol412
     */
    public function createResult($uikName, $data)
    {
        $uikNumber = str_replace('УИК №', '', $uikName);
        $result = ParserIKData_Model_Protocol::createFromPageInfo($uikNumber, '', array());
        /* @var $result ParserIKData_Model_Protocol412 */
        $result->setTypeOf();
        $result->setData($data);
        $result->setIkTypeUIK();
        return $result;
    }
}