<?php
require_once 'IData_store_umpire_admin.php';
class Array_store_umpire_admin extends CI_Model implements IData_store_umpire_admin
{

    private $umpiresAndValues = array(
        array(
            'id'=>1,
            'first_name'=>'John',
            'last_name'=>'Smith',
            'games_prior'=>120,
            'games_other_leagues'=>50
        ),
        array(
            'id'=>2,
            'first_name'=>'Sue',
            'last_name'=>'Jones',
            'games_prior'=>5,
            'games_other_leagues'=>3
        ),
        array(
            'id'=>3,
            'first_name'=>'Mark',
            'last_name'=>'Brown',
            'games_prior'=>12,
            'games_other_leagues'=>0
        )

    );

    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->model('Umpire');

    }

    public function getAllUmpiresAndValues() {
        return $this->umpiresAndValues;
    }

    public function updateUmpireRecords($pUmpireArray) {
        foreach($pUmpireArray as $arrayKey=>$umpireRecord) {
            //echo "loop 1 ";
            foreach($this->umpiresAndValues as $existingKey=>$existingUmpire) {
                //echo "loop 2 ";
                if($umpireRecord->getID() == $existingUmpire['id']) {
                    //Update record
                    //echo "match ";
                    $this->umpiresAndValues[$existingKey]['games_prior'] = $umpireRecord->getGamesPlayedPrior();
                    $this->umpiresAndValues[$existingKey]['games_other_leagues'] = $umpireRecord->getGamesPlayedOtherLeagues();
                }
            }
        }
    }

    public function logUmpireGamesHistory($pChangedUmpireArray) {


    }

    public function updateDimUmpireTable() {

    }

    public function updateMVReport8Table() {

    }



}