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
    
    public function updateSingleCompetition($pLeagueIDToUse, $competitionData);
    
    public function insertNewClub($pClubName);
    
    public function updateTeamTable($pTeamID, $pClubID);

    public function findSingleLeagueIDFromParameters($competitionData);

    public function insertNewLeague($competitionData);

    public function checkAndInsertAgeGroupDivision($competitionData);

    public function insertAgeGroupDivision($competitionData);

    public function updateTeamAndClubTables(IData_store $pDataStore, array $pPostData);

    
    //Match_import
    public function findSeasonToUpdate();
    
    public function findLatestImportedFile();
    
    public function runETLProcedure($pSeason, $pImportedFileID);
    
    //User
    public function findMatchingUserFromUsernameAndPassword($pUsername, $pPassword);

    public function checkUserActive($pUsername);

    public function getUserFromUsername($pUsername);

    public function findPermissionsForUser(User $pUser);

    public function checkUserExistsForReset(User $pUser);

    public function logPasswordResetRequest($pRequestData);

    public function storeActivationID($pActivationID, $pUser);

    public function createUserFromActivationID($pActivationID);

    public function updatePassword(User $pUser);

    public function logPasswordReset($pData);

    public function updateEmailAddress(User $pUser);
    
    public function loadSelectableReportOptions($pParameterID);

    public function findUserFromUsernameAndPassword($username, $password);
    
}
