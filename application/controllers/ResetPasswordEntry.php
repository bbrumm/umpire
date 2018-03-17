<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ResetPasswordEntry extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('string');
        $this->load->model('user');
    }
    
    function load($pActivationID) {
        $umpireUser = new User();
        $umpireUser->setActivationID($pActivationID);
        $data['activationIDMatches'] = $umpireUser->createUserFromActivationID();
        $data['activationID'] = $umpireUser->getActivationID();
        $data['username'] = $umpireUser->getUsername();
        
        
        $this->load->view('templates/header');
        $this->load->view('resetPassword', $data);
        $this->load->view('templates/footer');
        
    }
    
    function index() {
        $data['$activationIDMatches'] = true;
        
        $this->load->view('templates/header');
        $this->load->view('resetPassword', $data);
        $this->load->view('templates/footer');
        
        
    }
    
    private function showPasswordResetDonePage() {
        $this->load->view('templates/header');
        $this->load->view('password_reset_done');
        $this->load->view('templates/footer');
    }
    
    private function showPasswordResetEntryPage($pStatusMessage) {
        $data['statusMessage'] = $pStatusMessage;
        
        $umpireUser = new User();
        $umpireUser->setActivationID($_POST['activationID']);
        $data['activationIDMatches'] = $umpireUser->createUserFromActivationID();
        $data['activationID'] = $umpireUser->getActivationID();
        $data['username'] = $umpireUser->getUsername();
        
        $this->load->view('templates/header');
        $this->load->view('resetPassword', $data);
        $this->load->view('templates/footer');
    }
    
    public function submitNewPassword() {
        $userName = $_POST['username'];
        
        $newPassword= $this->security->xss_clean($this->input->post('password'));
        $confirmNewPassword= $this->security->xss_clean($this->input->post('confirmPassword'));
        
        $umpireUser = new User();
        
        $validPassword = $umpireUser->validatePassword($newPassword, $confirmNewPassword);
        
        if ($validPassword) {
            
            $umpireUser->setUsername($userName);
            $umpireUser->setPassword(MD5($newPassword));
            $umpireUser->updatePassword();
            $this->showPasswordResetDonePage();
        } else {
            $statusMessage = "Passwords do not match or are less than 6 characters. ".
                "Please ensure that both passwords you have entered are the same, and they are at least 6 characters long.";
            $this->showPasswordResetEntryPage($statusMessage);
        }
    }
    
    
    
    
    
}

?>