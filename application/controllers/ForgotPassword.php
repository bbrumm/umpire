<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ForgotPassword extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    function index()
    {
        $this->load->helper(array('form'));
        
        $this->load->view('templates/header');
        $this->load->view('password_reset');
        $this->load->view('templates/footer');
        
    }
    
    public function submitChangePasswordForm() {
        
        
        $this->showPasswordResetNextStepsPage();
    }
    
    private function showPasswordResetNextStepsPage() {
        $this->load->view('templates/header');
        $this->load->view('password_reset_next');
        $this->load->view('templates/footer');
        
    }
    
}

?>