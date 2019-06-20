<?php
//This interface is used to allow testing of the validation separate from database testing
interface IData_store_missing_data {

    //Missing_data_updater
    public function updateSingleCompetition($pLeagueIDToUse, $competitionData);

    public function insertNewClub($pClubName);

    public function updateTeamTable($pTeamID, $pClubID);

    public function findSingleLeagueIDFromParameters($competitionData);

    public function insertNewLeague($competitionData);

    public function checkAndInsertAgeGroupDivision($competitionData);

    public function insertAgeGroupDivision($competitionData);

    public function updateTeamAndClubTables(IData_store_matches $pDataStore, array $pPostData);

}