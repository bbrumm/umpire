<?php

include_once 'refreshmv.php';
include_once 'db.php';

define("ETL_NOT_STARTED", 2);
define("ETL_IN_PROGRESS", 3);
define("ETL_SUCCESS", 4);
define("ETL_ERROR", 5);


function findSeasonToUpdate() {
    $queryString = "SELECT MAX(season.ID) AS season_id " .
        "FROM season " .
        "INNER JOIN match_import ON season.season_year = match_import.season;";
    $rows = dbSelect($queryString);
    return $rows[0]['season_id'];
}

function getSeasonYearFromID($pSeasonID) {
    $queryString = "SELECT season_year ". 
        "FROM season " .
        "WHERE id = ". $pSeasonID .";";
    $rows = dbSelect($queryString);
    return $rows[0]['season_year'];
}

function updateImportFileStatus($pImportedFileID, $pStatusCode) {
    $queryString = "UPDATE imported_files SET etl_status = ". $pStatusCode . 
        " WHERE imported_file_id = ". $pImportedFileID .";";
    $rows = dbQuery($queryString);
}

function runETL() {
    try {
        //Look for row for imported file that needs to have the ETL steps run
        $rows = dbSelect("SELECT MAX(imported_file_id) AS imported_file_id FROM imported_files WHERE etl_status = ". ETL_NOT_STARTED.";");
        $importedFileID = $rows[0]['imported_file_id'];
        
        updateImportFileStatus($importedFileID, ETL_IN_PROGRESS);
        
        $seasonIDToUpdate = findSeasonToUpdate();
        $seasonYearToUpdate = getSeasonYearFromID($seasonIDToUpdate);
        
        //$rows = dbQuery("CALL `RunETLProcess`(". $seasonIDToUpdate.", ". $importedFileID .")");
        
        
        $rm = new RefreshMV();
        $rm->refreshMVTables($seasonIDToUpdate, $seasonYearToUpdate, $importedFileID);
        
        //Insert into test table
        $query = "INSERT INTO cron_result_test(textval, logged_date) VALUES (CONCAT('File ', ". $importedFileID."), NOW());";
        $rows = dbQuery($query);
        
        //Update status of import file
        updateImportFileStatus($importedFileID, ETL_SUCCESS);
        
        
        if ($rows === false){
        	$query = "INSERT INTO cron_result_test(textval, logged_date) VALUES ('Error 2', NOW());";
        	$rows = dbQuery($query);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        updateImportFileStatus($importedFileID, ETL_ERROR);
    }
}

?>