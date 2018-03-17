<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
    class UpdateProfile extends CI_Controller
    {
        
        function __construct() {
            parent::__construct();
            
            $this->load->helper('url_helper');
            $this->load->helper(array('form', 'url'));
            $this->load->model('User');
            $this->load->library('Debug_library');
        }
        
        function index() {
            $this->loadPage(NULL);
        }
        
        public function loadPage($pUserAddedMessage = "", $pMessageIsSuccess = FALSE) {
            $sessionData = $this->session->userdata('logged_in');
            
            $umpireUser = new User();
            $umpireUser->setUsername($sessionData['username']);
            $umpireUser = $this->lookupUserData($umpireUser);
            
            $data['username'] = $umpireUser->getUsername();
            $data['email_address'] = $umpireUser->getEmailAddress();
            $data['userAddedMessage'] = $pUserAddedMessage;
            $data['messageIsSuccess'] = $pMessageIsSuccess;
            
            $this->load->view('templates/header');
            $this->load->view('updateprofile', $data);
            $this->load->view('templates/footer');
        }
        
        public function updatePassword() {
            //TODO: Refactor this with the ResetPasswordEntry controller as it's very similar code
            $userName = $_POST['username'];
            
            $newPassword= $this->security->xss_clean($this->input->post('password'));
            $confirmNewPassword= $this->security->xss_clean($this->input->post('confirmPassword'));
            
            $umpireUser = new User();
            
            $validPassword = $umpireUser->validatePassword($newPassword, $confirmNewPassword);
            
            if ($validPassword) {
                
                $umpireUser->setUsername($userName);
                $umpireUser->setPassword(MD5($newPassword));
                $umpireUser->updatePassword();
                $statusMessage = "Password reset successfully.";
                $this->loadPage($statusMessage, TRUE);
            } else {
                $statusMessage = "Passwords do not match or are less than 6 characters. ".
                    "Please ensure that both passwords you have entered are the same, and they are at least 6 characters long.";
                $this->loadPage($statusMessage);
            }
        }
        
        public function updateEmail() {
            $userName = $_POST['username'];
            
            $newEmail= $this->security->xss_clean($this->input->post('email_address'));
            
            $umpireUser = new User();    
            
            $umpireUser->setUsername($userName);
            $umpireUser->setEmailAddress($newEmail);
            $umpireUser->updateEmailAddress();
            $statusMessage = "Email address updated successfully.";
            $this->loadPage($statusMessage, TRUE);
            
            
        }
        
        private function lookupUserData(User $pUser) {
            $dataLoaded = $pUser->getUserFromUsername($pUser->getUsername());
            
            return $pUser;
        }
        
        
        
    }
    ?>