<?php
class ImportFileSelector extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Report_instance');
		$this->load->helper('url_helper');
		$this->load->helper(array('form', 'url'));
	}
	
	public function index() {
		$data = array();
	        $data['test'] = "Test Report";
		$this->load->view('templates/header', $data);
		$this->load->view('upload_form', array('error' => ' ' , $data));
		$this->load->view('templates/footer');
		
	}
}
