<?php
class DataTest extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Requested_report_model');
		$this->load->helper('url_helper');
		$this->load->helper(array('form', 'url'));
		$this->load->model('Datatestmodel');
		
		
	}
	
	public function index() {
	    $data['test'] = "Test Report";
	    $data['css'] = $this->config->item('css');
		//    $this->load->view('templates/header', $data);
		
	    $data['umpireTestResultsArray'] = $this->Datatestmodel->runAllTests();
		//echo $data['umpireTestResultsArray'];
		
		$this->load->view('datatest', $data);
		
		//$this->load->view('templates/footer');
		
	}
	
	
}
