<?php
class ImportFileSelector extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('report_model');
		$this->load->helper('url_helper');
		$this->load->helper(array('form'));
	}
	
	public function index() {
		
		$data['title'] = 'Test Report';
		
		$this->load->view('templates/header', $data);
		$this->load->view('importFile', $data);
		$this->load->view('templates/footer');
		
	}
}
