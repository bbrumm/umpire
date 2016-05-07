<?php
class DataTest extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('report_model');
		$this->load->helper('url_helper');
		$this->load->helper(array('form', 'url'));
		$this->load->model('DataTestModel');
		
		
	}
	
	public function index() {
	    $data['test'] = "Test Report";
		$this->load->view('templates/header', $data);
		
		$data['umpireTestResultsArray'] = $this->DataTestModel->runAllTests();
		//echo $data['umpireTestResultsArray'];
		
		$this->load->view('datatest', $data);
		
		$this->load->view('templates/footer');
		
	}
	
	
}
