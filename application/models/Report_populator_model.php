<?php
class Report_populator_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->model('Report_instance');
		$this->load->library('Array_library');
		$this->load->model('Requested_report_model');
	}
	
	public function getReport(Requested_report_model $requestedReport) {
	    $requestedReport->setPDFMode(isset($_POST['PDFSubmitted']));
	    
		$reportToDisplay = new Report_instance();
		$reportToDisplay->setReportType($requestedReport);
		
		 /* To get the column labels, currently I get the query data for the report and then translate that.
		 * Is that the best way to go? Or should I use a different method of getting the column labels?
		 * If I keep doing it the same way, then I'll need to convert the report results to a collection
		 * of objects first, then convert the label arrays to objects.
		 * The function to get the report data should pass in the report parameters, and receive a collection
		 * of objects.
		 *  
		 */
		$reportToDisplay->loadReportResults();
		
		return $reportToDisplay;

	}

}