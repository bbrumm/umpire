<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
//session_start(); //we need to call PHP's session object to access it through CI
/*
* @property Object db
* @property Object session
*/
class Home extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->load->model('Report_selection_parameter');
        $this->load->model('Report_selection');
        $this->load->model('Season');
        $this->load->model('User');
        $this->load->model('Database_store_matches');

        if($this->session->userdata('logged_in')) {
            $this->session->userdata('logged_in');
            $data = array();
            $data['maxDateOutput'] = $this->getLatestImportDateOutput();
            $this->load->view('templates/header', $data);
            
            $data['reportSelectionParameters'] = $this->getAllReportSelectionParameters();
            $data['reportList'] = $this->getListOfReports();
            $data['seasonList'] = $this->getListOfSeasons();
            $data['validCombinations'] = $this->getListOfValidCombinations();
            
            $this->load->helper('form');
            if ($this->shouldUseNewReportLayout()) {
                $this->load->view('report_new', $data);
            } else {
                $this->load->view('report_home', $data);
            }
            $this->load->view('templates/footer');
        } else {
            //If no session, redirect to login page
            redirect('login', 'refresh');
        }
    }
    
    private function runQuery($queryString, $arrayParam = null) {
        return $this->db->query($queryString, $arrayParam);
    }
    
    public function logout() {
        $this->session->unset_userdata('logged_in');
        //$this->session->sess_destroy();
        //Reloads itself, causing the index() method above to be called.
        redirect('home', 'refresh');}
    //Closing bracket on above line so it's picked up by Coveralls correctly
    
    private function getLatestImportDate() {
        $queryString = "SELECT MAX(imported_datetime) as MAX_DATE FROM imported_files";
        $query = $this->runQuery($queryString);
        $maxDate = null;
        foreach ($query->result() as $row) {
            $maxDate = $row->MAX_DATE;         
        }
        return $maxDate;
    }
    
    private function getLatestImportDateOutput() {
        $dateFormatString = "d M Y, h:i:s A";
        $latestImportDate = $this->getLatestImportDate();
        return "Data last imported on " . date($dateFormatString, strtotime($latestImportDate));
    }
    
    private function getAllReportSelectionParameters() {
        $pDataStore = new Database_store_matches();
        
        $queryString = "SELECT parameter_id, parameter_name, parameter_display_order, allow_multiple_selections 
            FROM report_selection_parameters 
            ORDER BY parameter_display_order;";
        
        $query = $this->runQuery($queryString);
        $allReportSelectionParameters = array();
        foreach ($query->result() as $row) {
            $reportSelectionParameter = Report_selection_parameter::createReportSelectionParameter(
                $row->parameter_id, $row->parameter_name, 
                $row->parameter_display_order, $row->allow_multiple_selections
            );
            $reportSelectionParameter->initialiseSelectableReportOptions($pDataStore);
            $allReportSelectionParameters[] = $reportSelectionParameter;
        }
        return $allReportSelectionParameters;
    }

    private function getListOfReports() {
        $queryString = "SELECT
            report_id,
            report_name,
            region_enabled,
            league_enabled,
            age_group_enabled,
            umpire_type_enabled
            FROM report
            ORDER BY report_name ASC;";
        
        $query = $this->runQuery($queryString);
        $allReports = array();
        
        foreach ($query->result() as $row) {
            $reportSelection = Report_selection::createNewReportSelection(
                $row->report_id, $row->report_name, $row->region_enabled,
                $row->league_enabled, $row->age_group_enabled, $row->umpire_type_enabled);
            
            $allReports[] = $reportSelection;
        }
        return $allReports;
    }
    
    private function getListOfSeasons() {
        $queryString = "SELECT DISTINCT season_year
            FROM season
            ORDER BY season_year;";
        
        $query = $this->runQuery($queryString);
        $allSeasons = array();
        
        foreach ($query->result() as $row) {
            $season = new Season();
            $season->setSeasonYear($row->season_year);
            
            $allSeasons[] = $season;
        }
        return $allSeasons;
    }
    
    private function getListOfValidCombinations() {
        $queryString = "SELECT 
            v.id,
            pvr.parameter_value_name AS region,
            pvl.parameter_value_name AS league,
            pva.parameter_value_name AS age_group
            FROM valid_selection_combinations v
            INNER JOIN report_selection_parameter_values pvr ON pvr.parameter_value_id = v.pv_region_id
            INNER JOIN report_selection_parameter_values pvl ON pvl.parameter_value_id = v.pv_league_id
            INNER JOIN report_selection_parameter_values pva ON pva.parameter_value_id = v.pv_age_group_id;";
        
        $query = $this->runQuery($queryString);
        $queryResultArray = $query->result_array();
        return $queryResultArray;
    }

    private function shouldUseNewReportLayout() {
        $ffNewReportLayoutUsers = $this->getFeatureFlag('ff_new_report_selection');
        $session_data = $this->session->userdata('logged_in');
        $currentUsername = $session_data['username'];
        return in_array($currentUsername, $ffNewReportLayoutUsers);
    }

    private function getFeatureFlag($pFlagName) {
        return $this->config->item($pFlagName);
    }
}
