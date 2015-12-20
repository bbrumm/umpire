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
		
	    //$this->do_upload();
		$this->MatchImport->fileImport();
	}
	
	function do_upload()
	{
	    $config['upload_path'] = './application/import/';
	    $config['allowed_types'] = 'gif|jpg|png';
	    $config['max_size']	= '100';
	    $config['max_width']  = '1024';
	    $config['max_height']  = '768';
	
	    $this->load->library('upload', $config);
	
	    if ( ! $this->upload->do_upload())
	    {
	        $error = array('error' => $this->upload->display_errors());
	        
	        $data['test'] = "Test Report";
	        $this->load->helper(array('form', 'url'));
	        $this->load->view('templates/header', $data);
	        $this->load->view('importFile', $error);
	        $this->load->view('templates/footer');
	    }
	    else
	    {
	        $data = array('upload_data' => $this->upload->data());
	        $this->load->view('templates/header', $data);
	        $this->load->view('upload_success', $data);
	        $this->load->view('templates/footer');
	        
	    }
	}
		
	private function insertIntoMatchImportTable($pDataArray) {
		$insertQuery = '';
		
		
	}
}
