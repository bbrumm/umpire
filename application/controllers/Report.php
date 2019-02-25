<?php
class Report extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Report_populator_model');
		$this->load->helper('url_helper');
		$this->load->model('Cell_formatting_helper');
		$this->load->model('Requested_report_model');
		$this->load->library('Debug_library');
	}
	
	public function index() {
	    $data = array();
	    $reportPopulator = new Report_populator_model();
	    
	    $requestedReport = Requested_report_model::createRequestedReportFromValues(
	        intval($_POST['reportName']),
	        intval($_POST['season']),
	        $this->findValueFromPostOrHidden($_POST, 'rdRegion', 'chkRegionHidden'),
	        $this->findValueFromPostOrHidden($_POST, 'chkAgeGroup', 'chkAgeGroupHidden'),
	        $this->findValueFromPostOrHidden($_POST, 'chkUmpireDiscipline', 'chkUmpireDisciplineHidden'),
	        $this->findValueFromPostOrHidden($_POST, 'chkLeague', 'chkLeagueHidden'),
	        false
	        );
	    /*
	    $requestedReport = new Requested_report_model();
	    $requestedReport->setReportNumber(intval($_POST['reportName']));
	    $requestedReport->setSeason(intval($_POST['season']));

        */
	    /* Why are we treating these separately?
    	 * Maybe because when I submit the home page to the Report page, these chk keys exist.
    	 * When I 'submit' the Report page by clicking on Create PDF, they don't exist.
    	 */
	    /*
	    $requestedReport->setAgeGroup(
	       $requestedReport->findValueFromPostOrHidden($_POST, 'chkAgeGroup', 'chkAgeGroupHidden')); 
	    $requestedReport->setUmpireType(
	        $requestedReport->findValueFromPostOrHidden($_POST, 'chkUmpireDiscipline', 'chkUmpireDisciplineHidden'));
	    $requestedReport->setLeague(
	        $requestedReport->findValueFromPostOrHidden($_POST, 'chkLeague', 'chkLeagueHidden'));
	    $requestedReport->setRegion(
	        $requestedReport->findValueFromPostOrHidden($_POST, 'rdRegion', 'chkRegionHidden'));
	    */
	        $data['loadedReportItem'] = $reportPopulator->getReport($requestedReport);
		$data['title'] = 'Test Report';
		$data['PDFLayout'] = FALSE;
		$data['debugLibrary'] = new Debug_library();
		
		/*
		TODO: Also write some code to check if the passed value is "All", then it should look up all applicable values from
		the database and add them to the array, rather that look up a string of "All" in the table.
		*/
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
		$this->load->view('report/single_report_view', $data);
		$this->load->view('templates/footer');
		
	}
	
	//Determines if the Requested Report Model should use a value from the selectable field, or the hidden value
	//(e.g. if the PDF report was generated)
	private function findValueFromPostOrHidden($pPostArray, $pPostKey, $pPostKeyHidden) {
	    if (array_key_exists($pPostKey, $pPostArray)) {
	        return $_POST[$pPostKey];
	    } else {
	        return explode(",", $_POST[$pPostKeyHidden]);
	    }
	}
}
