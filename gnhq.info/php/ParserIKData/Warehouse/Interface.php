<?php
interface ParserIKData_Warehouse_Interface
{
    /**
    * @return ParserIKData_Warehouse_Interface
    */
    public function saveAllOkrugs();

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllOkrugs();

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function saveAllTiks();

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllTiks();

    /**
    * @return ParserIKData_Warehouse_Interface
    */
    public function saveAllUiks();

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadAllUiks();

}