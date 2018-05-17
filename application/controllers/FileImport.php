<?php
class FileImport extends CI_Controller {
    
    private $showSuccessPageWithoutImporting;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Report_instance');
		$this->load->helper('url_helper');
		$this->load->model('Match_import');
		$this->load->model('Run_etl_stored_proc');
		$this->load->model('Missing_data_updater');
		$this->load->helper('form');
		include 'application/helpers/phpexcel/Classes/PHPExcel.php';
		
		
	}
	
	public function index() {
	    
	}

	function do_upload()
	{
	    $config['upload_path'] = './application/import/';
	    $config['allowed_types'] = 'xlsx|xls';
	    $config['max_size']	= '4096';
	    $this->load->library('upload', $config);
	    
	    //echo "Upload Path(". $config['upload_path'] .")";
	     
        $uploadPassed = $this->upload->do_upload();

	    if ( ! $uploadPassed) {
	        $error = array('error' => $this->upload->display_errors());
	        $data['test'] = "Test Report";
	        $this->load->helper(array('form', 'url'));
	        $this->load->view('templates/header', $data);
	        $this->load->view('upload_form', $error);
	        $this->load->view('templates/footer');
	    } else {
	        $data = array('upload_data' => $this->upload->data());
	        $data['progress_pct'] = 10;
            $this->Match_import->fileImport($data);
	        $this->showUploadComplete();
	        
	    }
	}
	
	public function runETLProcess() {
	    //This function runs when the user presses "Update Reports" on the File Import page if missing data is found.
	    $this->Missing_data_updater->updateDataAndRunETLProcedure();
	    $this->showUploadComplete();
	}
	
	private function showUploadComplete() {
	    $data['missing_data'] = $this->Match_import->findMissingDataOnImport();
	    $data['possibleLeaguesForComp'] = $this->Missing_data_updater->loadPossibleLeaguesForComp();
	    $data['possibleClubsForTeam'] = $this->Missing_data_updater->loadPossibleClubsForTeam();
	    $data['possibleRegions'] = $this->Missing_data_updater->loadPossibleRegions();
	    $data['possibleAgeGroups'] = $this->Missing_data_updater->loadPossibleAgeGroups();
	    $data['possibleShortLeagueNames'] = $this->Missing_data_updater->loadPossibleShortLeagueNames();
	    $data['possibleDivisions'] = $this->Missing_data_updater->loadPossibleDivisions();
	    
	    $this->load->view('templates/header', $data);
	    $this->load->view('upload_success', $data);
	    $this->load->view('templates/footer');
	}
	
	
	public function updateCompetition() {
	    $selectedRegion = $_POST['selectedRegion'];
	    
	    echo "Ajax output";
	}
}