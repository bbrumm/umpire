<?php
require_once 'IData_store_missing_data.php';

class Array_store_missing_data extends CI_Model implements IData_store_missing_data
{

    public function __construct() {
        $this->load->library('Array_library');
    }

    public function updateSingleCompetition($pLeagueIDToUse, $competitionData) {
        //TODO write code
        $competitionArray = array(
            array(1, 3),
            array(2, 5),
            array(3, 4),
            array(4, 9)
        );
        $competitionArray[] = array(5, $competitionData["competition_id"]);
        return true;
    }

    public function insertNewClub($pClubName) {
        $clubArray = array('a', 'b', 'c', 'd');
        $clubArray[] = $pClubName;
        return $clubArray[4];


    }

    public function updateTeamTable($pTeamID, $pClubID) {
        //TODO write code
        $teamArray = array(
            array(1, 2),
            array(2, 5),
            array(3, 7)
        );

        foreach ($teamArray as $key => $value) {
            if ($value[0] == $pTeamID && $value[1] == $pClubID) {
                $teamArray[$key][1] = $pClubID;
                $updatedKey = $key;
            }
        }
        return (isset($updatedKey));

    }


    public function findSingleLeagueIDFromParameters($competitionData) {
        return 2;
    }

    public function insertNewLeague($competitionData) {
        $leagueArray = array('a', 'b', 'c', 'd', 'e');
        $leagueArray[] = $competitionData['short_league_name'];
        return $leagueArray[5];
    }

    public function checkAndInsertAgeGroupDivision($competitionData) {
        $arrayLibrary = new Array_library();
        $agdArray = array(
            'Under 14',
            'Under 16',
            'Under 18'
        );
        $countOfIDInArray = $arrayLibrary->in_array_r($competitionData["age_group"], $agdArray);
        if ($countOfIDInArray == 0) {
            $agdArray[] = $competitionData["age_group"];
        }
    }

    public function insertAgeGroupDivision($competitionData) {

    }

    public function updateTeamAndClubTables(IData_store_matches $pDataStore, array $pPostData) {

    }

}