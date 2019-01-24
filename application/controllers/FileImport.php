<?php


class FileImport extends CI_Controller {
	public function __construct() {
	    parent::__construct();
		
	    $this->load->model('Report_instance');
	    $this->load->helper('url_helper');
	    $this->load->model('Match_import');
	    $this->load->model('Run_etl_stored_proc');
        $this->load->model('File_loader_import');
	    $this->load->model('Missing_data_updater');
	    $this->load->model('Database_store_matches');
	    $this->load->helper('form');
	}

	function do_upload() {
	    $config['upload_path'] = './application/import/';
	    $config['allowed_types'] = 'xlsx|xls';
	    $config['max_size']	= '4096';
	    $this->load->library('upload', $config);

	    //Calls a CI method to upload the file from I think the POST variable
            $uploadPassed = $this->upload->do_upload();
            $data = array();
	    if ( ! $uploadPassed) {
	        $error = array('error' => $this->upload->display_errors());
	        $data['test'] = "Test Report";
	        $this->load->helper(array('form', 'url'));
	        $this->load->view('templates/header', $data);
	        $this->load->view('upload_form', $error);
	        $this->load->view('templates/footer');
	    } else {
                $data = array('upload_data' => $this->upload->data());
                //$data['progress_pct'] = 10;
                //Import the data from the imported spreadsheet
                $fileLoader = new File_loader_import();
                $dataStore = new Database_store_matches();
                $fileImportStatus = $this->Match_import->fileImport($fileLoader, $dataStore, $data);
                if ($fileImportStatus) {
                    $this->showUploadComplete();
                }
	    }
	}
	
	public function runETLProcess() {
	    //This function runs when the user presses "Update Reports" on the File Import page if missing data is found.
	    $databaseStore = new Database_store_matches();
	    $this->Missing_data_updater->updateDataAndRunETLProcedure($databaseStore, $_POST);
	    $this->showUploadComplete();
	}
	
	private function showUploadComplete() {
       	    $dataStore = new Database_store_matches();
	    $data = array();
	    $data['missing_data'] = $this->Match_import->findMissingDataOnImport();
	    $data['possibleLeaguesForComp'] = $this->Missing_data_updater->loadPossibleLeaguesForComp($dataStore);
	    $data['possibleClubsForTeam'] = $this->Missing_data_updater->loadPossibleClubsForTeam($dataStore);
	    $data['possibleRegions'] = $this->Missing_data_updater->loadPossibleRegions($dataStore);
	    $data['possibleAgeGroups'] = $this->Missing_data_updater->loadPossibleAgeGroups($dataStore);
	    $data['possibleShortLeagueNames'] = $this->Missing_data_updater->loadPossibleShortLeagueNames($dataStore);
	    $data['possibleDivisions'] = $this->Missing_data_updater->loadPossibleDivisions($dataStore);
	    
	    $this->load->view('templates/header', $data);
	    $this->load->view('upload_success', $data);
	    $this->load->view('templates/footer');
	}

	/*public function updateCompetition() {
	    $selectedRegion = $_POST['selectedRegion'];
	    echo "Ajax output";
	}*/
}
