<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start(); //we need to call PHP's session object to access it through CI
class Home extends CI_Controller {

 function __construct()
 {
   parent::__construct();
 }

 function index()
 {
     
   $this->load->model('ReportSelectionParameter');
   $this->load->model('User');
     
   if($this->session->userdata('logged_in'))
   {
     $session_data = $this->session->userdata('logged_in');
     
     
     //$data['currentUser'] = $currentUser;
     $data['maxDateOutput'] = $this->getLatestImportDateOutput();
	 
	 $this->load->view('templates/header', $data);
	 
	 $data['reportSelectionParameters'] = $this->getAllReportSelectionParameters();
	 
	 $this->load->helper('form');
	 $this->load->view('report_home', $data);
	 
	 $this->load->view('templates/footer');
	 
   }
   else
   {
     //If no session, redirect to login page
     redirect('login', 'refresh');
	 //echo "else";
   }
 }

 function logout()
 {
   $this->session->unset_userdata('logged_in');
   session_destroy();
   //Reloads itself, causing the index() method above to be called.
   redirect('home', 'refresh');
 }
 
 function getLatestImportDate() {
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
     
     //$allReportSelectionParameters[] = '';
     
     $queryString = "SELECT parameter_id, parameter_name, parameter_display_order, allow_multiple_selections " .
         "FROM report_selection_parameters " .
         "ORDER BY parameter_display_order;";
     
     $query = $this->db->query($queryString);
     foreach ($query->result() as $row) {
         $reportSelectionParameter = new ReportSelectionParameter();
         $reportSelectionParameter->setParameterID($row->parameter_id);
         $reportSelectionParameter->setParameterName($row->parameter_name);
         $reportSelectionParameter->setParameterDisplayOrder($row->parameter_display_order);
         $reportSelectionParameter->setAllowMultipleSelections($row->allow_multiple_selections);
         $reportSelectionParameter->loadSelectableReportOptions();
         
         $allReportSelectionParameters[] = $reportSelectionParameter;
         
         
     }
     return $allReportSelectionParameters;
 }
}
?>