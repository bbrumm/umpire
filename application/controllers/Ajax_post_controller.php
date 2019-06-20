<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
/*
* @property Object input
*/
class Ajax_Post_Controller extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        
        $this->load->helper('url_helper');
        $this->load->model('Umpireadminmodel');
        $this->load->model('Missing_data_updater');
        $this->load->model('data_store/Database_store_umpire_admin');
        $this->load->model('data_store/Database_store_missing_data');
        //include 'application/helpers/phpexcel/Classes/PHPExcel.php';

    }

    // This function call from AJAX
    public function user_data_submit() {
        $data = array(
            'username' => $this->input->post('name'),
            'pwd' => $this->input->post('pwd')
        );
        // Either you can print value or you can send value to database
        echo json_encode($data);
    }
    
    public function updateCompetition() {
        $data = array(
            'region' => $this->input->post('selectedRegion'),
            'age_group' => $this->input->post('selectedAgeGroup'),
            'division' => $this->input->post('selectedDivision'),
            'short_league_name' => $this->input->post('selectedLeague'),
            'competition_id' => $this->input->post('competitionID'),
        );
        
        $dataStore = new Database_store_missing_data();
        $missingDataUpdater = new Missing_data_updater();
        $missingDataUpdater->updateSingleCompetition($dataStore, $data);
    }
    
    public function updateUmpireGames() {
        $params = array();
        //$this->debug_library->debugOutput("post for ajax", $_POST);
        parse_str($_POST['data'], $params);
        
        // Either you can print value or you can send value to database
        $dataStore = new Database_store_umpire_admin();
        $umpireAdminModel = new Umpireadminmodel();
        $umpireAdminModel->updateUmpireGameValues($dataStore, $params);
    }

}
