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
    
    public function startDataImport() {
        //This is where I trigger the data import
        
        include "etltest.php";
        
        runETL();
        //TODO: Here, convert this to a function call inside etltest.php. But will this run in parallel?
        
    }
    
    
    
    
    
    public function getTestProgressValue() {
        $matchImport = new Match_import();
        return $matchImport->getProgressValueInDB();
    }
    
    
    
    public function updateProgressBar() {
        //Same code as checker?
        /*
         This is where I could check if the data import is complete
         Perhaps this is where the timer could go?
         I think this is where I need to output data using json_encode, in order to get the view page to read it
         Also use the loop and sleep function from process.php
         In the loop:
         - Create a table that lists the order of the tables impacted in hte import log
         - Query this table here against the latest import log to see how many tables have been processed
         - Loop through and work out a percent complete based on number of tables processed
         - Json_encode the percent
         */
        
        //The echo statement here is stored in the responseText from the Ajax request
        echo $this->getTestProgressValue();
        
        
        
    }
    
    
}

?>