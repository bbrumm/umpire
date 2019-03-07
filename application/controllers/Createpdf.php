<?php
/*
* @property Object config
*/
class CreatePDF extends CI_Controller {
	
	private $reportModel;
	
	function __construct() {
	    parent::__construct();
	    $this->load->model('user','',TRUE);
	    $this->load->model('Requested_report_model');
	    $this->load->helper('url_helper');
	    $this->load->helper('dompdf_helper');
	    $this->load->model('Cell_formatting_helper');
	}
	 
	 function writeToFile($outputText) {
	     $file = 'testOutput.txt';
	     // Open the file to get existing content
	     $current = file_get_contents($file);
	     // Append a new person to the file
	     $current .= $outputText . "\n";
	     // Write the contents back to the file
	     file_put_contents($file, $current);
	     
	 }
	
	function pdf() {

		$requestedReport = Requested_report_model::createRequestedReportFromValues(
	        intval($_POST['reportName']),
	        intval($_POST['season']),
	        $this->findValueFromPostOrHidden($_POST, 'rdRegion', 'chkRegionHidden'),
	        $this->findValueFromPostOrHidden($_POST, 'chkAgeGroup', 'chkAgeGroupHidden'),
	        $this->findValueFromPostOrHidden($_POST, 'chkUmpireDiscipline', 'chkUmpireDisciplineHidden'),
	        $this->findValueFromPostOrHidden($_POST, 'chkLeague', 'chkLeagueHidden'),
	        false
	        );
		
		
		$data = array();
		$data['loadedReportItem'] = $reportPopulator->getReport($requestedReport);
		$data['title'] = 'Test Report';
		$data['PDFLayout'] = TRUE;

		$this->load->helper(array('dompdf', 'file'));
		
		// page info here, db calls, etc.     
		
		$html = $this->load->view('templates/header', $data, TRUE);
		$html .= $this->load->view('report/pdf_report_view', $data, TRUE);
		$html .= $this->load->view('templates/footer', $data, TRUE);

		//Save To File (TRUE), or Output to Window (FALSE).
		$saveToFile = TRUE;
		if ($saveToFile) {
    		    pdf_create($html, 'pdf_report_view', $saveToFile, $data['loadedReportItem']->reportDisplayOptions);
		} else {
		    echo $html;
		}
	}
	
	//TODO: Remove this duplicate function (Report.php)
	private function findValueFromPostOrHidden($pPostArray, $pPostKey, $pPostKeyHidden) {
	    if (array_key_exists($pPostKey, $pPostArray)) {
	        return $_POST[$pPostKey];
	    } else {
	        return explode(",", $_POST[$pPostKeyHidden]);
	    }
	}
}
