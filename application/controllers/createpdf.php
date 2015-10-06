<?php

class CreatePDF extends CI_Controller {
	
	function __construct()
	 {
	   parent::__construct();
	   $this->load->model('user','',TRUE);
	   $this->load->model('report_model');
		$this->load->helper('url_helper');
		$this->load->helper('cell_formatting_helper');
	 }
	
	function pdf() {

		//$this->load->helper('pdf_helper');
		$reportParameters = array(
			'reportName' => $_POST['reportName'], 
			'season' => $_POST['season'], 
			'age' => $_POST['age'], 
			'umpireType' => $_POST['umpireType'], 
			'league' => $_POST['league']);
		
		
		$data['loadedReportItem'] = $this->report_model->get_report($reportParameters);
		$data['title'] = 'Test Report';
		$data['PDFLayout'] = TRUE;
		/*
		$this->load->view('report/single_report_view', $data);
		
		$this->load->view('pdfreport');
		*/
		
		$this->load->helper(array('dompdf', 'file'));
		// page info here, db calls, etc.     
		
		$html = $this->load->view('templates/header', $data, TRUE);
		$html .= $this->load->view('report/single_report_view', $data, TRUE);
		$html .= $this->load->view('templates/footer', $data, TRUE);
		
		pdf_create($html, 'single_report_view');
		//echo $html;
		
	}

}
?>