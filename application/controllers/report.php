<?php
class Report extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Report_populator_model');
		$this->load->helper('url_helper');
		$this->load->helper('cell_formatting_helper');
		$this->load->model('Requested_report_model');
		$this->load->library('Debug_library');
	}
	
	public function index() {
	    $error = "";
	    $reportPopulator = new Report_populator_model();
	    
	    $requestedReport = new Requested_report_model();
	    $requestedReport->setReportNumber(intval($_POST['reportName']));
	    $requestedReport->setSeason(intval($_POST['season']));
	    $requestedReport->setRegion($_POST['rdRegion']);

	    /* Why are we treating these separately?
    	 * Maybe because when I submit the home page to the Report page, these chk keys exist.
    	 * When I 'submit' the Report page by clicking on Create PDF, they don't exist.
    	 */
	    $requestedReport->setAgeGroup(
	       $requestedReport->findValueFromPostOrHidden($_POST, 'chkAgeGroup', 'chkAgeGroupHidden')); 
	    $requestedReport->setUmpireType(
	        $requestedReport->findValueFromPostOrHidden($_POST, 'chkUmpireDiscipline', 'chkUmpireDisciplineHidden'));
	    $requestedReport->setLeague(
	        $requestedReport->findValueFromPostOrHidden($_POST, 'chkLeague', 'chkLeagueHidden'));
	    $requestedReport->setRegion(
	        $requestedReport->findValueFromPostOrHidden($_POST, 'rdRegion', 'chkRegionHidden'));
	    
	    $data['loadedReportItem'] = $reportPopulator->getReport($requestedReport);
		$data['title'] = 'Test Report';
		$data['PDFLayout'] = FALSE;
		$data['debugLibrary'] = new Debug_library();
		
		//Note: You can't pass an array through a hidden POST variable.
		//This is why I have used the checkboxes and then imploded them
    	echo "<form method='post' id='reportPostValues' action='createpdf/pdf' target='_blank'>";
		echo "<input type='hidden' name='reportName' value='". $_POST['reportName'] ."' />";
		echo "<input type='hidden' name='season' value='". $_POST['season'] ."' />";
		echo "<input type='hidden' name='age' value='". $_POST['chkAgeGroupHidden'] ."' />";
		echo "<input type='hidden' name='umpireType' value='". $_POST['chkUmpireDisciplineHidden'] ."' />";
		echo "<input type='hidden' name='league' value='". $_POST['chkLeagueHidden'] ."' />";
		echo "<input type='hidden' name='region' value='". $_POST['chkRegionHidden'] ."' />";
		echo "<input type='hidden' name='PDFSubmitted' value='true' />";
		echo "</form>";	
		
		$this->load->view('templates/header', $data);
		$this->load->view('Report/single_report_view', $data);
		$this->load->view('templates/footer');
		
	}
}
