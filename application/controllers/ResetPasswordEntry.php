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
        $this->showHeader();
        $this->load->view('resetPassword', $data);
        $this->showFooter();
    }
    
     private function showPasswordResetDonePage() {
        $this->showHeader();
        $this->load->view('password_reset_done');
        $this->showFooter();
    }
    
    public function submitNewPassword() {
        $userAuthModel = new User_authentication_model();
        $dbStore = new Database_store_user();
        //TODO: Change this function so I don't need to pass the POST values, assuming it's only called in one place
        $passwordUpdated = $userAuthModel->updatePassword(
            $dbStore, $_POST['username'], $_POST['password'], $_POST['confirmPassword']);
        if($passwordUpdated) {
            $this->showPasswordResetDonePage();
        } else {
            $statusMessage = "Passwords do not match or are less than 6 characters. ".
                "Please ensure that both passwords you have entered are the same, and they are at least 6 characters long.";
            $this->showPasswordResetEntryPage($statusMessage);
        }
    }
    
    //TODO maybe move these functions to a common library?
    private function showHeader() {
        $this->load->view('templates/header');
    }
    
    private function showFooter() {
        $this->load->view('templates/footer');
    }
}
