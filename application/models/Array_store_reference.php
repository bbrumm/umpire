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

}