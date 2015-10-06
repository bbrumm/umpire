<?php
class Tabletest extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('report_model');
		$this->load->helper('url_helper');
		$this->load->helper('cell_formatting_helper');
	}
	
	public function index() {

		
		

		$this->load->view('tabletest.html');

		
	}
}
