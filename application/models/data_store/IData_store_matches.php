<?php
//This interface is used to allow testing of the validation separate from database testing
//More info: https://stackoverflow.com/questions/19937667/how-can-i-unit-test-a-method-with-database-access
interface IData_store_matches {
    //Match_import
    public function findSeasonToUpdate();
    
    public function findLatestImportedFile();
    
    public function runETLProcedure($pSeason, $pImportedFileID);

    //Report Instance
    public function loadSelectableReportOptions($pParameterID);

    public function loadReportData(Parent_report $separateReport, Report_instance $reportInstance);

    public function findLastGameDateForSelectedSeason(Requested_report_model $requestedReport);

    public function findDistinctColumnHeadings(IReport $separateReport, Report_instance $reportInstance);
    
}
