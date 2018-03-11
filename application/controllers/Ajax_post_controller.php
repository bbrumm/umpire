<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax_Post_Controller extends CI_Controller
{

    public function __construct() {
        parent::__construct();
        
        $this->load->helper('url_helper');
        $this->load->model('Umpireadminmodel');
        $this->load->model('Missing_data_updater');
        include 'application/helpers/phpexcel/Classes/PHPExcel.php';
        
        
    }
    
    // Show view Page
    public function index()
    {
        $this->load->helper('form');
        $this->load->view("ajax_post_view");
    }

    // This function call from AJAX
    public function user_data_submit()
    {
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
        
        // Either you can print value or you can send value to database
        $missingDataUpdater = new Missing_data_updater();
        $missingDataUpdater->updateSingleCompetition($data);
        
        //echo "updateCompetition test";
        
        //echo json_encode($data);
        
    }
    
    public function updateUmpireGames() {
        //echo "<script>console.log( 'abc' );</script>";
        $params = array();
        $this->debug_library->debugOutput("post for ajax", $_POST);
        parse_str($_POST['data'], $params);
        
        /*
         $data = array(
         'region' => $this->input->post('selectedRegion'),
         'age_group' => $this->input->post('selectedAgeGroup'),
         'division' => $this->input->post('selectedDivision'),
         'short_league_name' => $this->input->post('selectedLeague'),
         'competition_id' => $this->input->post('competitionID'),
         );*/
        
        // Either you can print value or you can send value to database
        //echo "updateUmpireGames test";
        $umpireAdminModel = new Umpireadminmodel();
        $umpireAdminModel->updateUmpireGameValues($params);
        
    }
    
    
}

?>