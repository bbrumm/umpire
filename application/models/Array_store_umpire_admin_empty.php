<?php
require_once 'IData_store_umpire_admin.php';
class Array_store_umpire_admin_empty extends CI_Model implements IData_store_umpire_admin
{

    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->model('Umpire');

    }

    public function getAllUmpiresAndValues() {
        $umpireArray = [];
        return $umpireArray;
    }

    public function updateUmpireRecords($pUmpireArray) {

    }

    public function logUmpireGamesHistory($pChangedUmpireArray) {


    }

    public function updateDimUmpireTable() {

    }

    public function updateMVReport8Table() {

    }



}