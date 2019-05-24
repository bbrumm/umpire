<?php
require_once 'IData_store_reference.php';

class Array_store_reference_empty extends CI_Model implements IData_store_reference
{
    public function getRoleArray() {
        $testData = [];
        return $testData;
    }

    public function getReportArray() {
        $testData = [];
        return $testData;
    }

    public function getRegionArray() {
        $testData = [];
        return $testData;
    }

    public function getUmpireDisciplineArray() {
        $testData = [];
        return $testData;
    }

    public function getAgeGroupArray() {
        $testData = [];
        return $testData;
    }

    public function getLeagueArray() {
        $testData = [];
        return $testData;
    }

    public function getPermissionSelectionArray() {
        $testData = [];
        return $testData;
    }

}