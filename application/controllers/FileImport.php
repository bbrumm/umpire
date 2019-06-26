<?php
/*
* @property Object upload
* @property Object Match_import
* @property Object Missing_data_updater
*/
class FileImport extends CI_Controller {
	
	const FILE_IMPORT_PATH = './application/import/';
	const IMPORT_ALLOWED_FILE_TYPES = 'xlsx|xls';
	const MAX_FILE_SIZE = '4096';
		
	public function __construct() {
	    parent::__construct();
		
	    $this->load->model('Report_instance');
	    $this->load->helper('url_helper');
	    $this->load->model('Match_import');
	    $this->load->model('Run_etl_stored_proc');
	    $this->load->model('File_loader_import');
	    $this->load->model('Missing_data_updater');
	    $this->load->model('data_store/Database_store_matches');
        $this->load->model('data_store/Database_store_missing_data');
        $this->load->model('data_store/Database_store_reference');
        $this->load->model('data_store/Database_store_match_import');
	    $this->load->helper('form');
	}

	function do_upload() {
	    $this->setConfigForUpload();
            $uploadPassed = $this->uploadFileFromPost();
	    if ( ! $uploadPassed) {
		    $this->displayUploadError();
	    } else {
		    $fileImportStatus = $this->importDataFromSpreadsheet();
                if ($fileImportStatus) {
                    $this->showUploadComplete();
                }
	    }
	}
	
	//Calls a CI method to upload the file from I think the POST variable
	private function uploadFileFromPost() {
	    return $this->upload->do_upload();
	}
	
	private function setConfigForUpload() {
	    $config = array();
	    $config['upload_path'] = self::FILE_IMPORT_PATH;
	    $config['allowed_types'] = self::IMPORT_ALLOWED_FILE_TYPES;
	    $config['max_size']	= self::MAX_FILE_SIZE;
	    $this->load->library('upload', $config);
	}
	
	private function displayUploadError() {
		$error = array('error' => $this->upload->display_errors());
	        $data['test'] = "Test Report";
	        $this->load->helper(array('form', 'url'));
	        $this->load->view('templates/header', $data);
	        $this->load->view('upload_form', $error);
	        $this->load->view('templates/footer');
	}
	
	private function importDataFromSpreadsheet() {
		$data = array('upload_data' => $this->upload->data());
        $fileLoader = new File_loader_import();
        $dataStore = new Database_store_match_import();
        $fileImportStatus = $this->Match_import->fileImport($fileLoader, $dataStore, $data);
		
		return $fileImportStatus;
	}

    //This function runs when the user presses "Update Reports" on the File Import page if missing data is found.
	public function runETLProcess() {
	    $databaseStore = new Database_store_matches();
	    $databaseStoreMissingData = new Database_store_missing_data();
	    $this->Missing_data_updater->updateCompetitionAndTeamData($databaseStoreMissingData, $_POST);
        $this->Missing_data_updater->runETLProcedure($databaseStore, $_POST);
	    $this->showUploadComplete();
	}
	
	private function showUploadComplete() {
	    $dataStore = new Database_store_matches();
	    $dataStoreReference = new Database_store_reference();
	    $data = array();
	    $data['missing_data'] = $this->Match_import->findMissingDataOnImport();
	    $data['possibleLeaguesForComp'] = $this->Missing_data_updater->loadPossibleLeaguesForComp($dataStoreReference);
	    $data['possibleClubsForTeam'] = $this->Missing_data_updater->loadPossibleClubsForTeam($dataStoreReference);
	    $data['possibleRegions'] = $this->Missing_data_updater->loadPossibleRegions($dataStoreReference);
	    $data['possibleAgeGroups'] = $this->Missing_data_updater->loadPossibleAgeGroups($dataStoreReference);
	    $data['possibleShortLeagueNames'] = $this->Missing_data_updater->loadPossibleShortLeagueNames($dataStoreReference);
	    $data['possibleDivisions'] = $this->Missing_data_updater->loadPossibleDivisions($dataStoreReference);
	    
	    $this->load->view('templates/header', $data);
	    $this->load->view('upload_success', $data);
	    $this->load->view('templates/footer');
	}

	/*public function updateCompetition() {
	    $selectedRegion = $_POST['selectedRegion'];
	    echo "Ajax output";
	}*/
}
