<?php
//This interface is used to allow testing of the validation separate from database testing
//More info: https://stackoverflow.com/questions/19937667/how-can-i-unit-test-a-method-with-database-access
interface IData_store {
    //Report_param_loader
    public function loadAllReportParameters($pReportNumber);
    
    public function loadAllGroupingStructures($pReportNumber);
    
    //Missing_data_updater
    public function loadPossibleLeaguesForComp();
    
    public function loadPossibleClubsForTeam();
    
    public function loadPossibleRegions();
    
    public function loadPossibleAgeGroups();
    
    public function loadPossibleShortLeagueNames();
    
    public function loadPossibleDivisions();
    
    public function updateSingleCompetition();
    
    public function insertNewClub($pClubName);
    
    public function updateTeamTable($pTeamID, $pClubID);
    
    //Match_import
    public function findSeasonToUpdate();
    
    public function findLatestImportedFile();
    
    public function runETLProcedure($pSeason, $pImportedFileID);
    
    //User
    public function userLogin($pUsername, $pPassword);

public function checkUserActive($pUsername);

public function getUserFromUsername($pUsername);

public function setPermissionArrayForUser();

public function checkUserExistsForReset();

public function logPasswordResetRequest($pRequestData);

public function storeActivationID($pActivationID);

public function createUserFromActivationID();

public function updatePassword();

public function logPasswordReset();

public function updateEmailAddress();
    
    public function loadSelectableReportOptions();
    
}
