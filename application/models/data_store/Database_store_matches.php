<?php
require_once 'IData_store_matches.php';
/*
* @property Object db
*/
class Database_store_matches extends CI_Model implements IData_store_matches {
    
    public function __construct() {
        $this->load->database();
        $this->load->library('Debug_library');
        $this->load->model('Etl_procedure_steps');

    }
    
    private function runQuery($queryString, $arrayParam = null) {
        return $this->db->query($queryString, $arrayParam);
    }
    
    public function loadAllReportParameters($pReportNumber) {
        $queryString = "SELECT
            t.report_name,
            t.report_title,
            t.value_field_id,
            t.no_value_display,
            t.first_column_format,
            t.colour_cells,
            p.orientation,
            p.paper_size,
            p.resolution
            FROM report t
            INNER JOIN t_pdf_settings p ON t.pdf_settings_id = p.pdf_settings_id
            WHERE t.report_id = ". $pReportNumber .";";
        
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        $countResultsFound = count($queryResultArray);
        
        if ($countResultsFound > 0) {
            $reportParameter = Report_parameter::createNewReportParameter(
                $queryResultArray[0]['report_title'],
                $queryResultArray[0]['value_field_id'],
                $queryResultArray[0]['no_value_display'],
                $queryResultArray[0]['first_column_format'],
                $queryResultArray[0]['colour_cells'],
                $queryResultArray[0]['orientation'],
                $queryResultArray[0]['paper_size'],
                $queryResultArray[0]['resolution']
                );
            
            return $reportParameter;
        } else {
            throw new Exception("No results found in the report table for this report number: " . $pReportNumber);
        }
    }
    
    public function loadAllGroupingStructures($pReportNumber) {
        $queryString = "SELECT rgs.report_grouping_structure_id, rgs.grouping_type, 
fl.field_name, rgs.field_group_order, rgs.merge_field, rgs.group_heading, rgs.group_size_text 
FROM report rt 
INNER JOIN report_grouping_structure rgs ON rt.report_name = rgs.report_id 
INNER JOIN field_list fl ON rgs.field_id = fl.field_id 
WHERE rt.report_id = ". $pReportNumber ."
ORDER BY rgs.grouping_type, rgs.field_group_order;";
        
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result();
        $countResultsFound = count($queryResultArray);
        $reportGroupingStructureArray = array();
        
        if ($countResultsFound > 0) {
            //Create report param and report grouping objects for this report
            //$this->debug_library->debugOutput("queryResultArray:",  $queryResultArray);

            foreach ($queryResultArray as $row) {

                $reportGroupingStructure = Report_grouping_structure::createNewReportGroupingStructure(
                    $row->report_grouping_structure_id,
                    $row->grouping_type,
                    $row->field_name,
                    $row->field_group_order,
                    $row->merge_field,
                    $row->group_heading,
                    $row->group_size_text
                    );
                $reportGroupingStructureArray[] = $reportGroupingStructure;
            }
            
            return $reportGroupingStructureArray;
        } else {
            throw new Exception("No results found in the report_grouping_structure table for this report number: " . $pReportNumber);
        }
    }

    /*
    private function getResultArrayFromQuery($queryString) {
        $query = $this->runQuery($queryString);
        
        if (mysqli_more_results($this->db->conn_id)) {
            mysqli_next_result($this->db->conn_id);
        }
        $resultArray = $query->result_array();
        $query->free_result();
        return $resultArray;
    }
    */

    
    



    
    //Match_import
    public function findSeasonToUpdate() {
        //echo "<pre>".print_r(debug_backtrace(2),true)."</pre>";

        $queryString = "SELECT MAX(season.ID) AS season_id " .
            "FROM season " .
            "INNER JOIN match_import ON season.season_year = match_import.season;";
        $query = $this->runQuery($queryString);
        $resultArray = $query->result_array();
        return $resultArray[0]['season_id'];
    }
    
    public function findLatestImportedFile() {
        $queryString = "SELECT MAX(imported_file_id) AS imported_file_id
            FROM table_operations";
        $query = $this->runQuery($queryString);
        $resultArray = $query->result_array();
        return $resultArray[0]['imported_file_id'];
    }
    
    public function runETLProcedure($pSeason, $pImportedFileID) {
        $etlSteps = new Etl_procedure_steps();
        $etlSteps->runETLProcess($pSeason, $pImportedFileID);
    }
    
    public function loadSelectableReportOptions($pParameterID) {
        $queryString = "SELECT parameter_value_name, parameter_display_order " .
            "FROM report_selection_parameter_values " .
            "WHERE parameter_id = $pParameterID " .
            "ORDER BY parameter_display_order;";
        
        $query = $this->runQuery($queryString);
        $selectableReportOptionsForParameter = array();
        
        foreach ($query->result() as $row) {
            //TODO: change this to a custom constructor
            $selectableReportOption = new Selectable_report_option();
            $selectableReportOption->setOptionName($row->parameter_value_name);
            $selectableReportOption->setOptionValue($row->parameter_value_name);
            $selectableReportOption->setOptionDisplayOrder($row->parameter_display_order);
            $selectableReportOptionsForParameter[] = $selectableReportOption;
        }
        return $selectableReportOptionsForParameter;
    }

    public function getUserNameFromActivationID(User $pUser) {}

    public function findOldUserPassword(User $pUser) {}

    public function checkUserFoundForUsername($pUsername) {}

    public function loadReportData(Parent_report $separateReport, Report_instance $reportInstance) {
        $queryForReport = $separateReport->getReportDataQuery($reportInstance);

        //Run query and store result in array
        $query = $this->runQuery($queryForReport);

        //Transform array to pivot
        $queryResultArray = $query->result_array();

        if (!isset($queryResultArray[0])) {
            throw new Exception("Result Array is empty. This is probably due to the SQL query not returning any results for the report.<BR />Query:<BR />" . $queryForReport);
        }
        return $queryResultArray;
    }

    public function loadReportDataNew(Parent_report $separateReport, Report_result $reportInstance) {
        $queryForReport = $separateReport->getReportDataQuery($reportInstance);

        //Run query and store result in array
        $query = $this->runQuery($queryForReport);

        //Transform array to pivot
        $queryResultArray = $query->result_array();

        if (!isset($queryResultArray[0])) {
            throw new Exception("Result Array is empty. This is probably due to the SQL query not returning any results for the report.<BR />Query:<BR />" . $queryForReport);
        }
        return $queryResultArray;
    }

    public function findLastGameDateForSelectedSeason(Requested_report_model $requestedReport) {
        $queryString = "SELECT DATE_FORMAT(MAX(match_time), '%a %d %b %Y, %h:%i %p') AS last_date 
            FROM match_played 
            INNER JOIN round ON round.id = match_played.round_id 
            INNER JOIN season ON season.id = round.season_id 
            WHERE season.season_year = ". $requestedReport->getSeason() .";";

        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray[0]['last_date'];
    }

    public function findDistinctColumnHeadings(IReport $separateReport, Report_instance $reportInstance) {
        $columnLabelQuery = $separateReport->getReportColumnQuery($reportInstance);

        $query = $this->runQuery($columnLabelQuery);
        return $query->result_array();
    }    
    
}
