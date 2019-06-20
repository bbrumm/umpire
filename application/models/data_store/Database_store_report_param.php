<?php
require_once 'IData_store_report_param.php';
/*
* @property Object db
*/
class Database_store_report_param extends CI_Model implements IData_store_report_param
{

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
            throw new Exception(
                "No results found in the report table for this report number: " . $pReportNumber);
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
            throw new Exception(
                "No results found in the report_grouping_structure table for this report number: " . $pReportNumber);
        }
    }

}