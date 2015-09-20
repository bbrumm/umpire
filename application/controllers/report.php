<?php
class report extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('report_model');
		$this->load->helper('url_helper');
	}
	
	
	public function index() {
		
		$data['loadedReportItem'] = $this->report_model->get_report();
		$data['title'] = 'Test Report';
		
		$this->load->view('templates/header', $data);
		$this->load->view('report/index', $data);
		$this->load->view('templates/footer');
		
	}
	
}