<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
* @property Object form_validation
* @property Object input
* @property Object session
*/
class VerifyLogin extends CI_Controller {

     function __construct() {
         parent::__construct();
         $this->load->model('user','',TRUE);
         $this->load->model('useradmin/User_authentication_model');
         $this->load->model('data_store/Database_store_user');

     }
    
     function index() {
         if($this->validateLoginForm()) {
             $this->loadMainReportSelectionPage();
         } else {
             $this->loadLoginPage();
         }
     }
     
     private function loadLoginPage() {
          $this->showHeader();
    	  	$this->load->view('login_view');
    		$this->showFooter();
     }
     
     private function loadMainReportSelectionPage() {
          redirect('home', 'refresh');
     }
     
     private function validateLoginForm() {
         $this->load->library('form_validation');
         $this->form_validation->set_rules('username', 'Username', 'trim|required');
         $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
         return $this->form_validation->run();
     }
     
     //TODO: Move these to a common location
     private function showHeader() {
          $this->load->view('templates/header');
     }
     
     private function showFooter() {
          $this->load->view('templates/footer');
     }
    
     function check_database($password) {
         $username = $this->input->post('username');
         $dbStore = new Database_store_user();
         $userAuth = new User_authentication_model();
         if ($userAuth->checkUserActive($dbStore, $username)) {
             $result = $this->loginUserAndReturnResult($userAuth, $dbStore, $username, $password);
             if ($result) {
                 $this->setUserData($result);
                 return true;
             } else {
                 $this->setInvalidUsernamePasswordMessage();
                 return false;
             }
         } else {
             $this->setUserInactiveMessage();
             return false;
         }
     }
     
     //TODO: Refactor, this has four parameters. Maybe create a Credentials object with username and password?
     private function loginUserAndReturnResult($userAuth, $dbStore, $username, $password) {
          return $userAuth->login($dbStore, $username, $password);
     }
     
     private function setUserData($result) {
          foreach($result as $row) {
           $sess_array = array(
               'id' => $row->id,
               'username' => $row->user_name
           );
           $this->session->set_userdata('logged_in', $sess_array);
       }
     }
     
     private function setInvalidUsernamePasswordMessage() {
          $this->form_validation->set_message('check_database', 'Invalid username or password.');
     }
     
     private function setUserInactiveMessage() {
          $this->form_validation->set_message('check_database', 'User is not active. Please contact support or the administrator.');
     }
}
