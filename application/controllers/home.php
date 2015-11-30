<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start(); //we need to call PHP's session object to access it through CI
class Home extends CI_Controller {

 function __construct()
 {
   parent::__construct();
 }

 function index()
 {
   if($this->session->userdata('logged_in'))
   {
     $session_data = $this->session->userdata('logged_in');
     $data['username'] = $session_data['username'];
	 
	 $this->load->view('templates/header', $data);
	 $this->load->helper('form');
	 $this->load->view('pages/report_home', $data);
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

}

?>