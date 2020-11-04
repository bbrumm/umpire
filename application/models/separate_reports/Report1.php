<?php
require_once 'IReport.php';
require_once 'Parent_report.php';

class Report1 extends Parent_report implements IReport {

    public $reportDataQueryFilename = "report1_data.sql";
    public $reportColumnQueryFilename = "report1_columns.sql";

    public function __construct() {
        $this->load->model('separate_reports/Report_query_builder');
        $this->load->model('separate_reports/Field_column_matcher');
    }

}
