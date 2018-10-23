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
        $this->load->model('useradmin/User_maintenance_model');
        $this->load->model('Database_store_matches');
    }
    
    function load($pActivationID) {
        $userMaintenance = new User_maintenance_model();
        $umpireUser = $userMaintenance->createUserFromActivationID($pActivationID);
        $umpireUser->setActivationID($pActivationID);
        //TODO check that this works and refactor. Repeated in function below.
        $data['activationIDMatches'] = isset($umpireUser);
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
        $userMaintenance = new User_maintenance_model();
        //TODO check that this works and refactor. Repeated in function above
        $umpireUser = $userMaintenance->createUserFromActivationID();
        $umpireUser->setActivationID($_POST['activationID']);
        $data['activationIDMatches'] = isset($umpireUser);
        $data['activationID'] = $umpireUser->getActivationID();
        $data['username'] = $umpireUser->getUsername();
        
        $this->load->view('templates/header');
        $this->load->view('resetPassword', $data);
        $this->load->view('templates/footer');
    }
    
    public function submitNewPassword() {
        $userName = $_POST['username'];
        $userMaintenance = new User_maintenance_model();
        $dbStore = new Database_store_user();
        
        $newPassword= $this->security->xss_clean($this->input->post('password'));
        $confirmNewPassword= $this->security->xss_clean($this->input->post('confirmPassword'));
        
        $umpireUser = new User();
        
        $validPassword = $userMaintenance->validatePassword($newPassword, $confirmNewPassword);
        
        if ($validPassword) {
            
            $umpireUser->setUsername($userName);
            $umpireUser->setPassword(MD5($newPassword));
            $userMaintenance->updatePassword($dbStore, $umpireUser);
            $this->showPasswordResetDonePage();
        } else {
            $statusMessage = "Passwords do not match or are less than 6 characters. ".
                "Please ensure that both passwords you have entered are the same, and they are at least 6 characters long.";
            $this->showPasswordResetEntryPage($statusMessage);
        }
    }
    
    
    
    
    
}

?>