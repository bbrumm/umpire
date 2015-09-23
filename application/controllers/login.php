<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login controller class
 */
class Login extends CI_Controller{
    
    function __construct(){
        parent::__construct();
    }
    
    public function index($msg = NULL){
        // Load our view to be displayed
        // to the user
        $data['msg'] = $msg;
		echo "TEST";
        $this->load->view('login_view', $data);
		
    }
    
    public function process(){
        // Load the model
        $this->load->model('login_model');
        // Validate the user can login
        $result = $this->login_model->validate();
		echo "Result:" . $result . "<BR />";

        // Now we verify the result
        if(! $result){
            // If user did not validate, then show them login page again
			echo "FAIL";
            $msg = '<font color=red>Invalid username and/or password.</font><br />';
            $this->index($msg);
        }else{
            // If user did validate, 
            // Send them to members area
			echo "PASS";
            redirect('home');
        }        
    }
}
?>