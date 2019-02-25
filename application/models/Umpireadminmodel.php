<?php
//require_once(__ROOT__.'/../system/libraries/MY_Model.php');

class Umpireadminmodel extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->model("Umpire");
        $this->load->library('Debug_library');
    }
    
    public function getAllUmpiresAndValues(IData_store_umpire_admin $pDataStore) {
        $umpireResultArray = $pDataStore->getAllUmpiresAndValues();
        $umpireArray = [];

        $arrayCount = count($umpireResultArray);
        if ($arrayCount == 0) {
            //No umpires found
            throw new Exception("No umpires found in the system.");
        }
        
        for($i=0; $i<$arrayCount; $i++) {
            $newUmpire = Umpire::createUmpireFromQueryResult($umpireResultArray[$i]);
            $umpireArray[] = $newUmpire;
        }
        return $umpireArray;
    }
    
    public function updateUmpireGameValues(IData_store_umpire_admin $pDataStore, $pPostArray) {

        $changedUmpireArray = $this->findChangedUmpires($pDataStore, $pPostArray);
        if (count($changedUmpireArray) > 0) {
            $pDataStore->updateUmpireRecords($changedUmpireArray);

            //TODO: Add check to see if this query ran successfully.
            $pDataStore->logUmpireGamesHistory($changedUmpireArray);

            //Update the dw_dim_umpire table
            $pDataStore->updateDimUmpireTable();

            //Also update the dw_mv_report_08 table
            $pDataStore->updateMVReport8Table();

            return true;
        }

    }

    private function findChangedUmpires(IData_store_umpire_admin $pDataStore, $pPostArray) {
        $existingUmpireValues = $this->getAllUmpiresAndValues($pDataStore);
        $currentLoopCount = 0;
        $umpireArray = [];
        $countChangedUmpires = 0;
        foreach ($pPostArray as $arrayKey=>$arrayValue) {
            $currentLoopCount++;
            //Find the Umpire object from the database results that matches this ID
            $matchedExistingUmpire = $this->findUmpireFromArrayUsingID($existingUmpireValues, $arrayKey);
            //Convert post array to Umpire object
            $currentUmpire = Umpire::createUmpireAllData($arrayKey, $matchedExistingUmpire->getFirstName(),
                $matchedExistingUmpire->getLastName(), $arrayValue['geelong_prior'], $arrayValue['other_leagues'],
                $matchedExistingUmpire->getGamesPlayedPrior(), $matchedExistingUmpire->getGamesPlayedOtherLeagues());

            //Check if values have changed, if so, update data
            if ($currentUmpire->haveUmpireGamesNumbersChanged()) {
                $countChangedUmpires++;
                $umpireArray[] = $currentUmpire;
            }
        }
        return $umpireArray;
    }


    
    private function findUmpireFromArrayUsingID($pUmpireArray, $pIDValue) {
        $matchedUmpire = new Umpire();
        $arrayCount = count($pUmpireArray);
        for ($i=0; $i < $arrayCount; $i++) {
            if ($pUmpireArray[$i]->getID() == $pIDValue) {
                $matchedUmpire = $pUmpireArray[$i];
            }
        }
        return $matchedUmpire;
    }
    
}
