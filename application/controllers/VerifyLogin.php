<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

     function __construct() {
         parent::__construct();
         $this->load->model('user','',TRUE);
         $this->load->model('useradmin/User_authentication_model');
         $this->load->model('Database_store_matches');

     }
    
     function index() {
         //This method will have the credentials validation
         $this->load->library('form_validation');
    
         $this->form_validation->set_rules('username', 'Username', 'trim|required');
         $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
         //$this->form_validation->set_rules('password', 'Password', 'trim|required');
    
         if($this->form_validation->run() == FALSE) {
             //Field validation failed.  User redirected to login page
      	     $this->load->view('templates/header');
    	  	 $this->load->view('login_view');
    		 $this->load->view('templates/footer');
         } else {
             //Go to private area
             redirect('home', 'refresh');
         }
     }
    
     function check_database($password) {
         //Field validation succeeded.  Validate against database
         $username = $this->input->post('username');
         $userAuth = new User_authentication_model();
         $dbStore = new Database_store_user();
    
         //Check if user is active first
         if($userAuth->checkUserActive($dbStore, $username)) {
             
             //query the database
             $result = $userAuth->login($dbStore, $username, $password);
             if($result) {
                 $sess_array = array();
                 foreach($result as $row) {
                     $sess_array = array(
                         'id' => $row->id,
                         'username' => $row->user_name
                     );
                     $this->session->set_userdata('logged_in', $sess_array);
                 }
                 return TRUE;
              } else {
                  $this->form_validation->set_message('check_database', 'Invalid username or password.');
                  return false;
              }
         
         
         } else {
             //User is not active
             $this->form_validation->set_message('check_database', 'User is not active. Please contact support or the administrator.');
             return false;
         }
         
     }
}
?>