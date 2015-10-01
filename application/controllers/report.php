<?php
class Report extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('report_model');
		$this->load->helper('url_helper');
		$this->load->helper('cell_formatting_helper');
	}
	
	public function index() {
		$reportParameters = array(
			'reportName' => $_POST['reportName'], 
			'season' => $_POST['season'], 
			'age' => $_POST['age'], 
			'umpireType' => $_POST['umpireType'], 
			'league' => $_POST['league']);
		$data['loadedReportItem'] = $this->report_model->get_report($reportParameters);
		$data['title'] = 'Test Report';
		
		
		echo "<form method='post' id='reportPostValues' action='createpdf/pdf' target='_blank'>";
		echo "<input type='hidden' name='reportName' value='". $_POST['reportName'] ."' />";
		echo "<input type='hidden' name='season' value='". $_POST['season'] ."' />";
		echo "<input type='hidden' name='age' value='". $_POST['age'] ."' />";
		echo "<input type='hidden' name='umpireType' value='". $_POST['umpireType'] ."' />";
		echo "<input type='hidden' name='league' value='". $_POST['league'] ."' />";
		echo "</form>";	
		
		
		$this->load->view('templates/header', $data);
		$this->load->view('report/single_report_view', $data);
		$this->load->view('templates/footer');
		
	}
}
