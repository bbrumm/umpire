<?php
/*
 * 2018_03_11
 * This code was split out from the RunETLProcess stored procedure in MySQL due to performance issues.
 * There are some issues with populating the dw_rpt06_stg2 table using a stored procedure,
 * where it takes 60 seconds inside a stored proc, compared to 0.2 seconds as a standard query.
 * This breaks the 60 second timeout on the server, as well as a poor user experience
 * So this model object will run the commands to refresh these tables as individual SQL queries.
 */

/*
* @property Object db
*/
class Refresh_mv_tables extends CI_Model {

    private $etlHelper;

    function __construct() {
        parent::__construct();
        $this->load->model('Season');
        $this->load->model('Etl_procedure_steps');
        //TODO: create a factory class that creates these reports
        $this->load->model('report_refresher/Report1_refresher');
        $this->etlHelper = new Report_table_refresher();
    }
    
    //TODO: A lot of this code is duplicated in model/Etl_procedure_steps.
    //Not the report tables, but the code to run queries and delete data
    public function refreshMVTables(IData_store_matches $pDataStore, $season, $importedFileID) {
        if (is_a($pDataStore, 'Array_store_matches')) {
            //TODO remove this once I have refactored this code
        } else {
            $this->refreshMVTable1($season, $importedFileID);
            $this->refreshMVTable2($season, $importedFileID);
            $this->refreshMVTable4($season, $importedFileID);
            $this->refreshMVTable5($season, $importedFileID);
            $this->refreshMVTable6($season, $importedFileID);
            $this->refreshMVTable7($season, $importedFileID);
            $this->refreshMVTable8($season, $importedFileID);
        }
    }

    //TODO: Replace all of these table names with variables
    private function refreshMVTable1($pSeason, $importedFileID) {
        $reportTableRefresher = Report1_refresher::createRefresher($importedFileID, $pSeason);
        $reportTableRefresher->refreshMVTable();
    }
    
    private function refreshMVTable2($pSeason, $importedFileID) {
        $reportTableRefresher = Report2_refresher::createRefresher($importedFileID, $pSeason);
        $reportTableRefresher->refreshMVTable();
    }
    
    private function refreshMVTable4($pSeason, $importedFileID) {
        $reportTableRefresher = Report4_refresher::createRefresher($importedFileID, $pSeason);
        $reportTableRefresher->refreshMVTable();
    }
    
    private function refreshMVTable5($pSeason, $importedFileID) {
        $reportTableRefresher = Report5_refresher::createRefresher($importedFileID, $pSeason);
        $reportTableRefresher->refreshMVTable();
    }
    
    private function refreshMVTable6($pSeason, $importedFileID) {
        $reportTableRefresher = Report6_refresher::createRefresher($importedFileID, $pSeason);
        $reportTableRefresher->refreshMVTable();
    }

    private function refreshMVTable7($pSeason, $importedFileID) {
        $reportTableRefresher = Report7_refresher::createRefresher($importedFileID, $pSeason);
        $reportTableRefresher->refreshMVTable();
    }

    private function refreshMVTable8($pSeason, $importedFileID) {
        $reportTableRefresher = Report8_refresher::createRefresher($importedFileID, $pSeason);
        $reportTableRefresher->refreshMVTable();
    }
}
    
