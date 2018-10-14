<?php
require_once 'IData_store.php';
class Array_store extends CI_Model implements IData_store {
    
    
    public function __construct() {
        
    }
    
    public function loadAllReportParameters($pReportNumber) {
        $reportParameterArray = [];
        $reportParameterArray[1] = Report_parameter::createNewReportParameter(
            'Random Title', 1, 0, 'text', true, 'portrait', 'a4', 200);
        $reportParameterArray[2] = Report_parameter::createNewReportParameter(
            'Another Title', 2, 0, 'text', false, 'landscape', 'a4', 200);
        $reportParameterArray[3] = Report_parameter::createNewReportParameter(
            'abc', 22, 0, 'number', true, 'portrait', 'a4', 200);
        $reportParameterArray[4] = Report_parameter::createNewReportParameter(
            'Report 4', 4, 0, 'text', false, 'landscape', 'a3', 200);
        $reportParameterArray[5] = Report_parameter::createNewReportParameter(
            'Siuerbibdfkvj', 5, 0, 'text', true, 'landscape', 'a4', 300);
        
        if(array_key_exists($pReportNumber, $reportParameterArray)) {
            return $reportParameterArray[$pReportNumber];
        } else {
            throw new Exception("Testing: No results found in the report table for this report number: " . $pReportNumber);
        }
    }
    
    public function loadAllGroupingStructures($pReportNumber) {
        $groupingStructureArray = [];
        $groupingStructureArray[0] = array(1, 1, 'Column', 'short_league_name', 1, 1, null, null);
        $groupingStructureArray[1] = array(1, 2, 'Column', 'club_name', 2, 0, null, null);
        $groupingStructureArray[2] = array(1, 3, 'Row', 'last_first_name', 1, 0, 'Name', 'Umpire_Name_First_Last');
        $groupingStructureArray[3] = array(2, 2, 'Column', 'age_group', 2, 0, null, null);
        
        $countArraySize = count($groupingStructureArray);
        $groupingStructureArrayForReport = [];
        for ($i = 0; $i < $countArraySize; $i++) {
            if ($groupingStructureArray[$i][0] == $pReportNumber) {
                $reportGroupingStructure = Report_grouping_structure::createNewReportGroupingStructure(
                    $groupingStructureArray[$i][1],
                    $groupingStructureArray[$i][2],
                    $groupingStructureArray[$i][3],
                    $groupingStructureArray[$i][4],
                    $groupingStructureArray[$i][5],
                    $groupingStructureArray[$i][6],
                    $groupingStructureArray[$i][7]
                    );
                $groupingStructureArrayForReport[] = $reportGroupingStructure;
            }
        }
        
        $countMatchingArraySize = count($groupingStructureArrayForReport);
        if ($countMatchingArraySize > 0) {
            return $groupingStructureArrayForReport;
        } else {
            throw new Exception("Testing: No results found in the report_grouping_structure table for this report number: " . $pReportNumber);
        }
    }
    
    public function loadPossibleLeaguesForComp() {
        $leaguesForComp = [];
        $leaguesForComp[0] = array(3, 'AFL Barwon Blood Toyota Geelong FNL', 'GFL', 1, 4, 'None', 'Seniors', 1, 'Geelong');
        $leaguesForComp[1] = array(4, 'AFL Barwon Buckleys Entertainment Centre Geelong FNL', 'GFL', 2, 4, 'None', 'Reserves', 1, 'Geelong');
        $leaguesForComp[2] = array(5, 'AFL Barwon Dow Bellarine FNL', 'BFL', 1, 4, 'None', 'Seniors', 1, 'Geelong');
        $leaguesForComp[3] = array(6, 'AFL Barwon Buckleys Entertainment Centre Bellarine FNL', 'BFL', 2, 4, 'None', 'Reserves', 1, 'Geelong');
        $leaguesForComp[4] = array(7, 'AFL Barwon', 'GJFL', 3, 5, 'Grading', 'Colts', 1, 'Geelong');
        $leaguesForComp[5] = array(8, 'AFL Barwon', 'GJFL', 4, 6, 'Practice', 'Colts', 1, 'Geelong');
        $leaguesForComp[6] = array(9, 'GDFL Smiths Holden Cup', 'GDFL', 1, 4, 'None', 'Seniors', 1, 'Geelong');
        $leaguesForComp[7] = array(10, 'GDFL Buckleys Cup', 'GDFL', 2, 4, 'None', 'Reserves', 1, 'Geelong');
        $leaguesForComp[8] = array(11, 'AFL Barwon', 'GJFL', 5, 5, 'Grading', 'Under 16', 1, 'Geelong');
        $leaguesForComp[9] = array(12, 'AFL Barwon', 'GJFL', 6, 5, 'Grading', 'Under 14', 1, 'Geelong');
        
        return $leaguesForComp;
        
    }
    
    public function loadPossibleClubsForTeam() {
        $clubs = [];
        $clubs[0] = array(0, 'North');
        $clubs[1] = array(1, 'South');
        $clubs[2] = array(2, 'East');
        $clubs[3] = array(3, 'West');
        
        return $clubs;
    }
    
    public function loadPossibleRegions() {
        $regions = [];
        $regions[0] = array(0, 'Aldo');
        $regions[1] = array(1, 'Samba');
        $regions[2] = array(2, 'Down');

        return $regions;
    }
    
    public function loadPossibleAgeGroups() {
        $ages = [];
        $ages[0] = array(0, 'Under 12');
        $ages[1] = array(1, 'Under 14');
        $ages[2] = array(2, 'Under 16');
        $ages[3] = array(3, 'Under 18');
        $ages[4] = array(4, 'Under 19');
        
        return $ages;
    }
    
    public function loadPossibleShortLeagueNames() {
        $leagues = [];
        $leagues[0] = array(0, 'WAFL');
        $leagues[1] = array(1, 'SANFL');
        $leagues[2] = array(2, 'NEAFL');
        $leagues[3] = array(3, 'VFL');

        return $leagues;
    }
    
    public function loadPossibleDivisions() {
        $divisions = [];
        $divisions[0] = array(0, 'Div 1');
        $divisions[1] = array(1, 'Div 2');
        $divisions[2] = array(2, 'Div 3');
        
        return $divisions;
    }
    
    public function updateSingleCompetition() {
        
    }
    
    public function insertNewClub($pClubName) {
        
    }
    
    public function updateTeamTable($pTeamID, $pClubID) {
        
    }
    
    public function findSeasonToUpdate() {
        
    }
    
    public function findLatestImportedFile() {
        
    }
    
    public function runETLProcedure($pSeason, $pImportedFileID) {
        
    }
    
    public function userLogin($pUsername, $pPassword) { }
public function checkUserActive($pUsername) { }
public function getUserFromUsername($pUsername) { }
public function setPermissionArrayForUser() { }
public function checkUserExistsForReset() { }
public function logPasswordResetRequest($pRequestData) { }
public function storeActivationID($pActivationID) { }
public function createUserFromActivationID() { }
public function updatePassword() { }
public function logPasswordReset() { }
public function updateEmailAddress() { }
    
    
}
