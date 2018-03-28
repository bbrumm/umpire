<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

     function __construct() {
         parent::__construct();
         $this->load->model('user','',TRUE);
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
    
         //Check if user is active first
         if($this->user->checkUserActive($username)) {
             
             //query the database
             $result = $this->user->login($username, $password);
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