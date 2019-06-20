<?php
require_once 'IData_store_matches.php';

class Array_store_matches extends CI_Model implements IData_store_matches
{


    public function __construct() {
        $this->load->library('Array_library');
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



    public function loadReportData(Parent_report $separateReport, Report_instance $reportInstance) {
        $resultArray = array (
            array('last_first_name'=>'john', 'short_league_name'=>'GFL', 'club_name'=>'Geelong', 'age_group'=>'Under 18', 'umpire_type'=>'Field', 'match_count'=>2),
            array('last_first_name'=>'john', 'short_league_name'=>'BFL', 'club_name'=>'Hawthorn', 'age_group'=>'Under 18', 'umpire_type'=>'Field', 'match_count'=>1),
            array('last_first_name'=>'sue', 'short_league_name'=>'GFL', 'club_name'=>'Melbourne', 'age_group'=>'Under 18', 'umpire_type'=>'Field', 'match_count'=>5),
            array('last_first_name'=>'sue', 'short_league_name'=>'BFL', 'club_name'=>'Hawthorn', 'age_group'=>'Under 18', 'umpire_type'=>'Field', 'match_count'=>7),
            array('last_first_name'=>'mark', 'short_league_name'=>'BFL', 'club_name'=>'Hawthorn', 'age_group'=>'Under 18', 'umpire_type'=>'Field', 'match_count'=>7),
            array('last_first_name'=>'matt', 'short_league_name'=>'BFL', 'club_name'=>'Essendon', 'age_group'=>'Under 18', 'umpire_type'=>'Field', 'match_count'=>7),
            array('last_first_name'=>'matt', 'short_league_name'=>'GFL', 'club_name'=>'Carlton', 'age_group'=>'Seniors', 'umpire_type'=>'Field', 'match_count'=>7)

        );

        return $resultArray;
    }

    public function findLastGameDateForSelectedSeason(Requested_report_model $requestedReport) {

    }

    public function findDistinctColumnHeadings(IReport $separateReport, Report_instance $reportInstance) {
        switch ($reportInstance->requestedReport->getReportNumber()) {
            case 1:
                return array(
                    array('short_league_name' => 'GFL', 'club_name' => 'Geelong'),
                    array('short_league_name' => 'GFL', 'club_name' => 'Melbourne'),
                    array('short_league_name' => 'GFL', 'club_name' => 'Carlton'),
                    array('short_league_name' => 'BFL', 'club_name' => 'Hawthorn'),
                    array('short_league_name' => 'BFL', 'club_name' => 'Essendon')

                );
            case 6:
                return array(
                    array('last_first_name' => 'john'),
                    array('last_first_name' => 'mark'),
                    array('last_first_name' => 'matt'),
                    array('last_first_name' => 'sue')
                );
            case 4:
                return array(
                    array('umpire_type' => 'Field', 'age_group' => 'Under 18', 'short_league_name' => 'GFL'),
                    array('umpire_type' => 'Field', 'age_group' => 'Under 18', 'short_league_name' => 'BFL'),
                    array('umpire_type' => 'Field', 'age_group' => 'Seniors', 'short_league_name' => 'GFL')

                );
            default:
                return array(
                    array('short_league_name' => 'GFL', 'club_name' => 'Geelong'),
                    array('short_league_name' => 'GFL', 'club_name' => 'Melbourne'),
                    array('short_league_name' => 'GFL', 'club_name' => 'Carlton'),
                    array('short_league_name' => 'BFL', 'club_name' => 'Hawthorn'),
                    array('short_league_name' => 'BFL', 'club_name' => 'Essendon')
                );
        }

    }




}
