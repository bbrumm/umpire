<?php
require_once 'IData_store.php';

class Array_store extends CI_Model implements IData_store
{


    public function __construct() {
        $this->load->library('Array_library');
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

        if (array_key_exists($pReportNumber, $reportParameterArray)) {
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

    public function updateTeamAndClubTables(IData_store $pDataStore, array $pPostData) {

    }

    public function findSeasonToUpdate() {
        //TODO write code
    }

    public function findLatestImportedFile() {
            //TODO write code
    }

    public function runETLProcedure($pSeason, $pImportedFileID) {
        //TODO write code
    }


    public function getUserFromUsername($pUsername) {
        $existingData = array(
            array("username"=>"abcdef", "first_name"=>"andy", "last_name"=>"jones"),
            array("username"=>"qwe", "first_name"=>"quinten", "last_name"=>"johnson"),
            array("username"=>"asd", "first_name"=>"sam", "last_name"=>"smith"),
            array("username"=>"asd", "first_name"=>"sam2", "last_name"=>"smith2"),
            array("username"=>"john", "first_name"=>"john", "last_name"=>"smith"),
            array("username"=>"paul", "first_name"=>"paul", "last_name"=>"smith"),
            array("username"=>"george", "first_name"=>"george", "last_name"=>"smith")
        );

        $userRow = [];

        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["username"] == $pUsername) {
                $user = User::createUserFromNameAndRole(1, $existingData[$i]["username"],
                    $existingData[$i]["first_name"], $existingData[$i]["last_name"],
                    null, 1, null);
                return $user;
            }
        }


    }


    public function setPermissionArrayForUser() {
    }

    public function findPermissionsForUser(User $pUser) {
        $permissionArrayJohn = array (
            array("id"=>1, "permission_id"=>1, "permission_name"=>"IMPORT_FILES", "selection_name"=>"All"),
            array("id"=>2, "permission_id"=>2, "permission_name"=>"VIEW_USER_ADMIN", "selection_name"=>"All"),
            array("id"=>3, "permission_id"=>3, "permission_name"=>"VIEW_DATA_TEST", "selection_name"=>"All"),
            array("id"=>4, "permission_id"=>4, "permission_name"=>"CREATE_PDF", "selection_name"=>"All")

        );

        $permissionArrayPaul = array (
            array("id"=>1, "permission_id"=>1, "permission_name"=>"IMPORT_FILES", "selection_name"=>"All")

        );

        $permissionArrayGeorge = array (
            array("id"=>1, "permission_id"=>1, "permission_name"=>"IMPORT_FILES", "selection_name"=>"Some"),
            array("id"=>2, "permission_id"=>2, "permission_name"=>"VIEW_USER_ADMIN", "selection_name"=>"Some"),
            array("id"=>3, "permission_id"=>3, "permission_name"=>"VIEW_DATA_TEST", "selection_name"=>"Some"),
            array("id"=>4, "permission_id"=>4, "permission_name"=>"CREATE_PDF", "selection_name"=>"Some")

        );

        if($pUser->getUsername() == "john") {
            return $permissionArrayJohn;
        } elseif($pUser->getUsername() == "paul") {
            return $permissionArrayPaul;
        } elseif($pUser->getUsername() == "george") {
            return $permissionArrayGeorge;
        } else {
            return $permissionArrayJohn;
        }

    }

    public function checkUserExistsForReset(User $pUser) {
        $userArray = array(
            "test", "something", "another", "bongo"
        );
        $userFound = false;
        $arrayCount = count($userArray);
        for ($i=0; $i<$arrayCount; $i++) {
            if ($userArray[$i] == $pUser->getUsername()) {
                $userFound = true;
            }
        }
        return $userFound;
    }

    public function logPasswordResetRequest($pRequestData) {
        $existingArray = [];
        $existingArray[] = $pRequestData;
        return true;
    }

    public function storeActivationID($pUser, $pActivationID) {
        $recordFound = false;
        $existingData = array(
            array("username"=>"abcdef", "email_address"=>"test@abc.com")
        );
        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            //echo "username (". $pUser->getUsername() ."), email (".$pUser->getEmailAddress() .") <BR />";
            if ($existingData[$i]["username"] == $pUser->getUsername() &&
                $existingData[$i]["email_address"] == $pUser->getEmailAddress()) {
                $recordFound = true;
            }
        }
        return $recordFound;

    }

    public function createUserFromActivationID($pActivationID) {
        $existingData = array(
           array("username"=>"abcdef", "activation_id"=>"123456"),
            array("username"=>"qwe", "activation_id"=>"123"),
            array("username"=>"asd", "activation_id"=>"111"),
            array("username"=>"zxc", "activation_id"=>"111")
        );

        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["activation_id"] == $pActivationID) {
                $user = new User();
                $user->setUsername($existingData[$i]["username"]);
                return $user;
            }
        }

    }
    
    public function getUserNameFromActivationID(User $pUser) {
        $existingData = array(
           array("username"=>"abcdef", "activation_id"=>"123456"),
            array("username"=>"qwe", "activation_id"=>"123"),
            array("username"=>"asd", "activation_id"=>"111"),
            array("username"=>"zxc", "activation_id"=>"111")
        );

        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["activation_id"] == $pUser->getActivationID()) {
                return $existingData[$i]["username"];
            }
        } 
    }


    public function updatePassword(User $pUser) {
        $updateStatus = false;
        $existingData = array(
            array("username"=>"abcdef", "password"=>"mypass"),
            array("username"=>"john", "password"=>"one"),
            array("username"=>"john", "password"=>"two"),
            array("username"=>"another", "password"=>"three")
        );
        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["username"] == $pUser->getUsername()) {
                if ($pUser->getPassword() != null && $pUser->getPassword() != "") {
                    $existingData[$i]["password"] == $pUser->getPassword();
                    $updateStatus = true;
                }
            }
        }
        return $updateStatus;
    }

    public function findOldUserPassword(User $pUser) {
        return "some_old_password";
    }

    public function logPasswordReset($pData) {
        $existingData = [];
        $existingData[] = $pData;
        return true;

    }

    public function updateEmailAddress(User $pUser) {
        $updateStatus = false;
        $existingData = array(
            array("username"=>"test", "email_address"=>"email1"),
            array("username"=>"john", "email_address"=>"email1one"),
            array("username"=>"john", "email_address"=>"email1two"),
            array("username"=>"another", "email_address"=>"email1three")
        );
        $arrayCount = count($existingData);
        for($i=0; $i<$arrayCount; $i++) {
            if ($existingData[$i]["username"] == $pUser->getUsername()) {
                $updateStatus = true;
                $existingData[$i]["email_address"] == $pUser->getEmailAddress();
            }
        }
        return $updateStatus;
    }

    public function findUserFromUsernameAndPassword($username, $password) {


    }

    public function loadSelectableReportOptions($pParameterID) {
        $testData = array(
            array(1, "Some option name", 1),
            array(4, "Some option name", 1),
            array(4, "Another option name", 2)
        );
        $countElements = count($testData);
        $selectableReportOptionArray = [];
        for ($i = 0; $i < $countElements; $i++) {
            if ($testData[$i][0] == $pParameterID) {
                $selectableReportOption = new Selectable_report_option();
                $selectableReportOption->setOptionName($testData[$i][1]);
                $selectableReportOption->setOptionDisplayOrder($testData[$i][2]);
                $selectableReportOptionArray[] = $selectableReportOption;
            }

        }
        return $selectableReportOptionArray;

    }

    public function findMatchingUserFromUsernameAndPassword($pUsername, $pPassword) {
        $testData = array(
            array("id" => 1, "username" => "john", "password" => MD5("mypass")),
            array("id" => 2, "username" => "paul", "password" => MD5(null)),
            array("id" => 3, "username" => "ringo", "password" => MD5("otherthing")),
            array("id" => 4, "username" => "george", "password" => MD5("theword")),
            array("id" => 5, "username" => "george", "password" => MD5("another")),
            array("id" => 6, "username" => "ringo", "password" => MD5("otherthing"))
        );

        foreach ($testData as $key => $subArray) {
            if ($subArray["username"] == $pUsername && $subArray["password"] == MD5($pPassword)) {
                return User::createUserFromNameAndPW($subArray["username"], null, null, $subArray["password"], $subArray["id"]);
            }
        }


    }


    public function checkUserActive($pUsername) {
        $testData = array(
            array("id" => 1, "username" => "john", "active" => 1),
            array("id" => 2, "username" => "paul", "active" => 0),
            array("id" => 3, "username" => "ringo", "active" => 1),
            array("id" => 4, "username" => "george", "active" => 1),
            array("id" => 5, "username" => "ringo", "active" => 0),
            array("id" => 6, "username" => "george", "active" => 1)
        );
        $rowCount = 0;

        foreach ($testData as $key => $subArray) {
            if ($subArray["username"] == $pUsername && $subArray["active"] == 1) {
                $rowCount++;
            }
        }
        return ($rowCount == 1);

    }


}
