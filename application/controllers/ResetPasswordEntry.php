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
        $this->load->model('useradmin/User_authentication_model');
        $this->load->model('Database_store_user');
    }

    /*
     * This is called when the user clicks on the link in the email.
     * A check is made against the activationID in the database to ensure it's not being faked.
     */

    function load($pActivationID) {
        $data = $this->populateDataArrayForView($pActivationID);
        $this->showResetPasswordPage($data);
    }
    
    private function showPasswordResetEntryPage($pStatusMessage) {
        $data = $this->populateDataArrayForView($_POST['activationID']);
        $data['statusMessage'] = $pStatusMessage;
        $data['activationIDMatches'] = isset($umpireUser); //different
        $this->showResetPasswordPage($data);
    }
    
    private function populateDataArrayForView($pActivationID) {
        $userMaintenance = new User_maintenance_model();
        $dataStore = new Database_store_user();
        $umpireUser = $userMaintenance->createUserFromActivationID($dataStore, $pActivationID);
        $data['activationID'] = $umpireUser->getActivationID();
        $data['username'] = $umpireUser->getUsername();
        return $data;
    }
    
    private function showResetPasswordPage($data) {
        $this->load->view('templates/header');
        $this->load->view('resetPassword', $data);
        $this->load->view('templates/footer');
    }
    
     private function showPasswordResetDonePage() {
        $this->load->view('templates/header');
        $this->load->view('password_reset_done');
        $this->load->view('templates/footer');
    }
    
    public function submitNewPassword() {
        $userAuthModel = new User_authentication_model();
        $dbStore = new Database_store_user();
        $passwordUpdated = $userAuthModel->updatePassword($dbStore, $_POST['username'], $_POST['password'], $_POST['confirmPassword']);
        if($passwordUpdated) {
            $this->showPasswordResetDonePage();
        } else {
            $statusMessage = "Passwords do not match or are less than 6 characters. ".
                "Please ensure that both passwords you have entered are the same, and they are at least 6 characters long.";
            $this->showPasswordResetEntryPage($statusMessage);
        }
    }
}
