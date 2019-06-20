<?php
require_once 'IData_store_report_param.php';

class Array_store_report_param extends CI_Model implements IData_store_report_param
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
            throw new Exception(
                "Testing: No results found in the report table for this report number: " . $pReportNumber);
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
            throw new Exception(
                "Testing: No results found in the report_grouping_structure table for this report number: " . $pReportNumber);
        }
    }

}