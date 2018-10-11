<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start(); //we need to call PHP's session object to access it through CI
class Home_acc extends CI_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        
        $this->load->model('Report_selection_parameter');
        $this->load->model('Report_selection');
        $this->load->model('Season');
        $this->load->model('User');
        
        if($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $data['maxDateOutput'] = $this->getLatestImportDateOutput();
            $this->load->view('templates/header', $data);
            
            
            $data['reportSelectionParameters'] = $this->getAllReportSelectionParameters();
            $data['reportList'] = $this->getListOfReports();
            $data['seasonList'] = $this->getListOfSeasons();
            $this->load->helper('form');
            $this->load->view('report_acc', $data);
            $this->load->view('templates/footer');
            
        } else {
            //If no session, redirect to login page
            redirect('login', 'refresh');
        }
    }
    
    public function logout() {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        //Reloads itself, causing the index() method above to be called.
        redirect('home', 'refresh');
    }
    
    private function getLatestImportDate() {
        $queryString = "SELECT MAX(imported_datetime) as MAX_DATE FROM imported_files";
        $query = $this->db->query($queryString);
        foreach ($query->result() as $row) {
            $maxDate = $row->MAX_DATE;
        }
        return $maxDate;
    }
    
    public function getLatestImportDateOutput() {
        $dateFormatString = "d M Y, h:i:s A";
        $latestImportDate = $this->getLatestImportDate();
        return "Data last imported on " . date($dateFormatString, strtotime($latestImportDate));
    }
    
    private function getAllReportSelectionParameters() {
        $queryString = "SELECT parameter_id, parameter_name, parameter_display_order, allow_multiple_selections
            FROM report_selection_parameters
            ORDER BY parameter_display_order;";
        
        $query = $this->db->query($queryString);
        foreach ($query->result() as $row) {
            $reportSelectionParameter = Report_selection_parameter::createReportSelectionParameter(
                $row->parameter_id, $row->parameter_name, 
                $row->parameter_display_order, $row->allow_multiple_selections);
            );
            
            $allReportSelectionParameters[] = $reportSelectionParameter;
        }
        return $allReportSelectionParameters;
    }
    
    private function getListOfReports() {
        $queryString = "SELECT report_id, report_name
            FROM t_report
            ORDER BY report_name ASC;";
        
        $query = $this->db->query($queryString);
        
        foreach ($query->result() as $row) {
            $reportSelection = new Report_selection();
            $reportSelection->setReportID($row->report_id);
            $reportSelection->setReportName($row->report_name);
            
            $allReports[] = $reportSelection;
        }
        return $allReports;
    }
    
    private function getListOfSeasons() {
        $queryString = "SELECT DISTINCT season_year
            FROM season
            ORDER BY season_year;";
        
        $query = $this->db->query($queryString);
        
        foreach ($query->result() as $row) {
            $season = new Season();
            $season->setSeasonYear($row->season_year);
            
            $allSeasons[] = $season;
        }
        return $allSeasons;
    }
}
?>