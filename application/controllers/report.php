<?php
class Report extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('report_model');
		$this->load->helper('url_helper');
		$this->load->helper('cell_formatting_helper');
		
	}
	
	public function index() {
	    /*echo "POST<pre>";
	    print_r($_POST);
	    echo "</pre><BR />";
	    */
	    $debugMode = $this->config->item('debug_mode');
	    $error = "";
	    
	    if ($debugMode) {
    	    echo "Post: <br /><pre>";
    	    print_r($_POST);
    	    echo "</pre>";
    	}
	    /*
	    $ageGroupSelections = $_POST['chkAgeGroup'];
	    print_r($ageGroupSelections);
	    foreach ($ageGroupSelections as $ageGroup){
	        echo $ageGroup."<br />";
	    
	    }*/

    	$reportParameters = array(
    	    'reportName' => $_POST['reportName'],
    	    'season' => $_POST['season'],
    	    'region' => $_POST['rdRegion'],
    	);
    	
    	/*Why are we treating these separately?
    	 * Maybe because when I submit the home page to the report page, these chk keys exist.
    	 * When I 'submit' the report page by clicking on Create PDF, they don't exist.
    	 * 
    	 */
    	if (array_key_exists('chkAgeGroup', $_POST)) {
    	    $reportParameters['age'] = $_POST['chkAgeGroup'];
    	} else {
    	    $reportParameters['age'] = explode(",", $_POST['chkAgeGroupHidden']);
    	}
    	
    	if (array_key_exists('chkUmpireDiscipline', $_POST)) {
    	    $reportParameters['umpireType'] = $_POST['chkUmpireDiscipline'];
    	} else {
    	    $reportParameters['umpireType'] = explode(",", $_POST['chkUmpireDisciplineHidden']);
    	}
    	
    	if (array_key_exists('chkLeague', $_POST)) {
    	    $reportParameters['league'] = $_POST['chkLeague'];
    	} else {
    	    $reportParameters['league'] = explode(",", $_POST['chkLeagueHidden']);
    	}
    	
    	if (array_key_exists('rdRegion', $_POST)) {
    	    $reportParameters['region'] = $_POST['rdRegion'];
    	} else {
    	    $reportParameters['region'] = explode(",", $_POST['chkRegionHidden']);
    	}
    	 
    	
    	
    	if ($debugMode) {
    	    echo "reportParameters in report.php: <br /><pre>";
    	    print_r($reportParameters);
    	    echo "</pre>";
    	    echo "POST in report.php:<pre>";
    	    print_r($_POST);
    	    echo "</pre>";
    	}
	    
	    
	    /*
			'age' => $_POST['chkAgeGroup'], 
			'umpireType' => $_POST['chkUmpireDiscipline'], 
			'league' => $_POST['chkLeague']);
			
			*/
		$data['loadedReportItem'] = $this->report_model->get_report($reportParameters);
		$data['title'] = 'Test Report';
		$data['PDFLayout'] = FALSE;
		
		
		
		//Note: You can't pass an array through a hidden POST variable.
		//This is why I have used the checkboxes and then imploded them
    	echo "<form method='post' id='reportPostValues' action='createpdf/pdf' target='_blank'>";
		//echo "<form method='post' id='reportPostValues' action='createpdf/pdfUsingTCPDF' target='_blank'>";
		echo "<input type='hidden' name='reportName' value='". $_POST['reportName'] ."' />";
		echo "<input type='hidden' name='season' value='". $_POST['season'] ."' />";
		echo "<input type='hidden' name='age' value='". $_POST['chkAgeGroupHidden'] ."' />";
		echo "<input type='hidden' name='umpireType' value='". $_POST['chkUmpireDisciplineHidden'] ."' />";
		echo "<input type='hidden' name='league' value='". $_POST['chkLeagueHidden'] ."' />";
		echo "<input type='hidden' name='region' value='". $_POST['chkRegionHidden'] ."' />";
		echo "<input type='hidden' name='PDFSubmitted' value='true' />";
		echo "</form>";	
		
		//$this->load->library('DebugLibrary');
		$this->load->view('templates/header', $data);
		$this->load->view('report/single_report_view', $data);
		$this->load->view('templates/footer');
		
		
	}
}
