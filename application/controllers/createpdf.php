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

		$this->load->helper('pdf_helper');
	/*
	---- ---- ---- ----
	your code here
	---- ---- ---- ----
	*/
		/*
		$data['loadedReportItem'] = $this->report_model->get_report();
		$data['title'] = 'Test Report';
		
		$this->load->view('report/single_report_view', $data);
		*/
		$this->load->view('pdfreport');
	}

}
?>