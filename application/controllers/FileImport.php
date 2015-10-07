<?php
class FileImport extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('report_model');
		$this->load->helper('url_helper');
		$this->load->model('MatchImport');
		
		//$this->load->helper('cell_formatting_helper');
		//$this->load->helper('phpexcel/Classes/PHPExcel');
		include 'application/helpers/phpexcel/Classes/PHPExcel.php';
	}
	
	public function index() {
		
		$this->MatchImport->fileImport();
		
		
	}
	
	private function insertIntoMatchImportTable($pDataArray) {
		$insertQuery = '';
		
		
	}
}
