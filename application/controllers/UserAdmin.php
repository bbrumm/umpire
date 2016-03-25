<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserAdmin extends CI_Controller {

 function __construct()
 {
   parent::__construct();
 }

 function index()
 {
   $this->load->helper(array('form'));
   
   $this->load->view('templates/header');
   $this->load->view('useradmin');
   $this->load->view('templates/footer');
	 
 }

}

?>