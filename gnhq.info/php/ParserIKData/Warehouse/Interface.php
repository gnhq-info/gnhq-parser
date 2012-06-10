<?php
require_once ('Csv.php');
require_once ('MySql.php');

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

    /**
    * @return ParserIKData_Warehouse_Interface
    */
    public function saveElectionResults($electionCode, $resultType);

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadElectionResults($electionCode, $resultType);

    /**
    * @return ParserIKData_Warehouse_Interface
    */
    public function saveElectionReports($electionCode);

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadElectionReports($electionCode);

    /**
    * @return ParserIKData_Warehouse_Interface
    */
    public function saveElectionWatches($electionCode, $watchType);

    /**
     * @return ParserIKData_Warehouse_Interface
     */
    public function loadElectionWatches($electionCode, $watchType);

}