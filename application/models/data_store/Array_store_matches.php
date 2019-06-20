<?php
require_once 'IData_store_matches.php';

class Array_store_matches extends CI_Model implements IData_store_matches
{


    public function __construct() {
        $this->load->library('Array_library');
    }

    public function loadAllReportParameters($pReportNumber) {
        $reportParameterArray = [];
        $reportParameterArray[1] = Report_parameter::createNewReportParameter(
            'Random Title %seasonYear', 1, 0, 'text', true, 'portrait', 'a4', 200);
        $reportParameterArray[2] = Report_parameter::createNewReportParameter(
            'Another Title', 2, 0, 'text', false, 'landscape', 'a4', 200);
        $reportParameterArray[3] = Report_parameter::createNewReportParameter(
            'abc', 22, 0, 'number', true, 'portrait', 'a4', 200);
        $reportParameterArray[4] = Report_parameter::createNewReportParameter(
            'Report 4', 4, 0, 'text', false, 'landscape', 'a3', 200);
        $reportParameterArray[5] = Report_parameter::createNewReportParameter(
            'Siuerbibdfkvj', 5, 0, 'text', true, 'landscape', 'a4', 300);
        $reportParameterArray[6] = Report_parameter::createNewReportParameter(
            'something', 6, 0, 'text', true, 'landscape', 'a4', 300);

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
        $groupingStructureArray[4] = array(6, 2, 'Column', 'last_first_name', 1, 0, null, null);
        $groupingStructureArray[5] = array(6, 2, 'Row', 'umpire_type', 1, 0, null, null);
        $groupingStructureArray[6] = array(4, 2, 'Column', 'umpire_type', 1, 0, null, null);
        $groupingStructureArray[7] = array(4, 2, 'Column', 'age_group', 2, 0, null, null);
        $groupingStructureArray[8] = array(4, 2, 'Column', 'short_league_name', 3, 0, null, null);
        $groupingStructureArray[9] = array(4, 2, 'Row', 'last_first_name', 1, 0, null, null);

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
