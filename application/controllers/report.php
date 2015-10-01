<?php
class Report extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('report_model');
		$this->load->helper('url_helper');
		$this->load->helper('cell_formatting_helper');
	}
	
	public function index() {
		$data['loadedReportItem'] = $this->report_model->get_report();
		$data['title'] = 'Test Report';
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('report/single_report_view', $data);
		$this->load->view('templates/footer');
		
	}
}
