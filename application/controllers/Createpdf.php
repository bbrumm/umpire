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
        $this->load->model('Report_populator_model');
	    $this->load->helper('url_helper');
	    $this->load->helper('dompdf_helper');
	    $this->load->model('Cell_formatting_helper');
	}
	
	function pdf() {

	    /*
	     * POST:

	    Array
        (
            [reportName] => 1
            [season] => 2018
            [age] => Seniors,
            [umpireType] => Field,
            [league] => BFL,
            [region] => Geelong,
            [PDFSubmitted] => true
        )
	     */


		/*$requestedReport = Requested_report_model::createRequestedReportFromValues(
	        intval($_POST['reportName']),
	        intval($_POST['season']),

	        $this->findValueFromPostOrHidden($_POST, 'rdRegion', 'chkRegionHidden'),
	        $this->findValueFromPostOrHidden($_POST, 'chkAgeGroup', 'chkAgeGroupHidden'),
	        $this->findValueFromPostOrHidden($_POST, 'chkUmpireDiscipline', 'chkUmpireDisciplineHidden'),
	        $this->findValueFromPostOrHidden($_POST, 'chkLeague', 'chkLeagueHidden'),
	        false
	        );
		*/

        $requestedReport = $this->createNewReportFromPostData();
		
		$data = array();

        $reportPopulator = new Report_populator_model();

		$data['loadedReportItem'] = $reportPopulator->getReport($requestedReport);
		$data['title'] = 'Test Report';
		//$data['PDFLayout'] = FALSE;
		$data['printerFriendly'] = TRUE;

		//$this->load->helper(array('dompdf', 'file'));
		
		// page info here, db calls, etc.     

        //Don't output the header when PDF is being used, so we can create a printer-friendly mode
		$html = $this->load->view('templates/header', $data, TRUE);
        $html .= $this->load->view('report/single_report_view', $data, TRUE);
		//$html .= $this->load->view('templates/footer', $data, TRUE);

		//Save To File (TRUE), or Output to Window (FALSE).
		//$saveToFile = TRUE;
        //$saveToFile = FALSE;
        /*
		if ($saveToFile) {
    		    pdf_create($html, 'pdf_report_view', $saveToFile, $data['loadedReportItem']->reportDisplayOptions);
		} else {
		    echo $html;
		}

        */
        echo $html;
	}

	private function createNewReportFromPostData() {
	    $pdfMode = false;
	    return Requested_report_model::createRequestedReportFromValues(
            intval($_POST['reportName']),
            intval($_POST['season']),
            $_POST['region'],
            $_POST['age'],
            $_POST['umpireType'],
            $_POST['league'],
            $pdfMode
        );
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
