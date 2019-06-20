<?php
require_once 'IData_store_reference.php';

class Array_store_reference extends CI_Model implements IData_store_reference
{

    public function getRoleArray() {
        $testData = array(
            array("id"=>1, "role_name"=>"Administrator", "display_order"=>1),
            array("id"=>2, "role_name"=>"Super User", "display_order"=>2),
            array("id"=>3, "role_name"=>"Regular User", "display_order"=>3)
        );

        return $testData;
    }

    public function getReportArray() {
        $testData = array (
            array("report_id"=>1, "report_title"=>"01 - Umpires and Clubs"),
            array("report_id"=>2, "report_title"=>"02 - Umpire Names by League"),
            array("report_id"=>3, "report_title"=>"03 - Summary")
        );

        return $testData;
    }

    public function getRegionArray() {
        $testData = array (
            array("id"=>1, "region"=>"Geelong"),
            array("id"=>2, "region"=>"Colac")
        );

        return $testData;
    }

    public function getUmpireDisciplineArray() {
        $testData = array (
            array("id"=>1, "umpire_type_name"=>"Field"),
            array("id"=>2, "umpire_type_name"=>"Boundary"),
            array("id"=>3, "umpire_type_name"=>"Goal")
        );

        return $testData;
    }

    public function getAgeGroupArray() {
        $testData = array (
            array("id"=>1, "umpire_type_name"=>"Under 18"),
            array("id"=>2, "umpire_type_name"=>"Under 16"),
            array("id"=>3, "umpire_type_name"=>"Senior")
        );

        return $testData;
    }

    public function getLeagueArray() {
        $testData = array (
            array("id"=>1, "short_league_name"=>"BFL"),
            array("id"=>2, "short_league_name"=>"GFL"),
            array("id"=>3, "short_league_name"=>"GDFL")
        );

        return $testData;
    }

    public function getPermissionSelectionArray() {
        $testData = array (
            array("id"=>1, "permission_id"=>1, "category" => "something", "selection_name" => "yes"),
            array("id"=>2, "permission_id"=>1, "category" => "else", "selection_name" => "two"),
            array("id"=>3, "permission_id"=>5, "category" => "more", "selection_name" => "blah")
        );

        return $testData;
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

}