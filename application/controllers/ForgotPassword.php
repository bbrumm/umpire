<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
/*
* @property Object input
* @property Object security
* @property Object email
*/
class ForgotPassword extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('user');
        $this->load->helper('string');
        $this->load->model('useradmin/User_maintenance_model');
        $this->load->model('useradmin/User_permission_loader_model');
        $this->load->model('Database_store_matches');
        
    }
    
    function index()
    {
        $this->load->helper(array('form'));
        
        $this->load->view('templates/header');
        $this->load->view('password_reset');    
        $this->load->view('templates/footer');
        
    }
    
    public function submitChangePasswordForm($pSendEmail = true) {
        $sendStatusInfo = $this->validateUserAndSendEmail($pSendEmail);
        if ($sendStatusInfo['status'] == "sent") {
            //Show page
            $this->showPasswordResetNextStepsPage($sendStatusInfo);
        } else {
            $this->showPasswordResetPage($sendStatusInfo);
            
        }
    }
    
    private function getCleanUsername() {
        return $this->getXSSCleanValue('username');
    }
    
    private function getCleanEmailAddress() {
        return $this->getXSSCleanValue('emailAddress');
    }
    
    private function getXSSCleanValue($pValue) {
        return $this->security->xss_clean($this->input->post($pValue));
    }
    
    private function showPasswordResetNextStepsPage($sendStatusInfo) {
        $data = array();
        $data['sendStatus'] = $sendStatusInfo['status'];
        $data['sendStatusMessage'] = $sendStatusInfo['message'];
        $data['passwordResetURL'] = $sendStatusInfo['passwordResetURL'];
        
        $this->load->view('templates/header');
        $this->load->view('password_reset_next', $data);
        $this->load->view('templates/footer');
    }
    
    private function showPasswordResetPage($sendStatusInfo) {
        $data = array();
        $data['sendStatus'] = $sendStatusInfo['status'];
        $data['sendStatusMessage'] = $sendStatusInfo['message'];
        
        $this->load->helper(array('form'));
        $this->load->view('templates/header');
        $this->load->view('password_reset', $data);
        $this->load->view('templates/footer');
    }
    
    //TODO: refactor, maybe split function
    private function validateUserAndSendEmail($pSendEmail) {
        $umpireUser = new User();
        $username = $this->getCleanUsername();
        $emailAddress = $this->getCleanEmailAddress();
        
        $umpireUser->setUsername($username);
        $umpireUser->setEmailAddress($emailAddress);
        
        $userMaintenance = new User_maintenance_model();
        $userPermissionLoader = new User_permission_loader_model();
        $dbStore = new Database_store_user();
        
        $data = $this->populateDataArray($umpireUser);

        $userMaintenance->logPasswordResetRequest($dbStore, $data);

        //Check user data entered: user exists, email matches user
        $userExists = $userMaintenance->checkUserExistsForReset($dbStore, $umpireUser);
        if($userExists) {
            urlencode($pEmailAddress);
            $userPermissionLoader->getUserFromUsername($dbStore, $pUserName);
            $umpireUser->setPasswordResetURL(base_url() . "index.php/ResetPasswordEntry/load/" . $data['activation_id']);
            $userMaintenance->storeActivationID($dbStore, $umpireUser, $data['activation_id']);

            //Send the email only if the flag is set. Flag is set to false for component testing.
            if ($pSendEmail) {
                $sendStatus = $this->sendPasswordResetEmail($umpireUser);
            } else {
                $sendStatus = true;
            }
        } else {
            $sendStatus = false;
        }
        $sendStatusInfo = $this->getSendStatusInfo($umpireUser, $sendStatus, $userExists);
        return $sendStatusInfo;
    }
    
    private function populateDataArray($umpireUser) {
        $data = array();
        $data['activation_id'] = random_string('alnum',15);
        $data['request_datetime'] = date('Y-m-d H:i:s');
        $data['client_ip'] = $this->input->ip_address();
        $data['username_entered'] = $umpireUser->getUsername();
        $data['email_address_entered'] = $umpireUser->getEmailAddress();
        return $data;
    }

    private function getSendStatusInfo(User $pUmpireUser, $pSendStatus, $pUserExists) {
        $sendStatusInfo = array();
        if ($pUserExists) {
            if ($pSendStatus) {
                $sendStatusInfo['status'] = "sent";
                $sendStatusInfo['message'] =
                    "Please check your email for a link to reset your password.";
                $sendStatusInfo['passwordResetURL'] = $pUmpireUser->getPasswordResetURL();
            } else {
                $sendStatusInfo['status'] = "not sent";
                $sendStatusInfo['message'] =
                    "The email has failed to send. Please check the data you have entered and try again, or contact support.";
            }
        } else {
            $sendStatusInfo['status'] = "incorrect";
            $sendStatusInfo['message'] =
                "Username or email address not found. Please try again or contact support.";
        }

        return $sendStatusInfo;
    }
    
    /*private function sendPasswordResetEmail(User $pUser) {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://umpirereporting.com',
            'smtp_port' => 465,
            'smtp_user' => 'support@umpirereporting.com', // change it to yours
            'smtp_pass' => 'LT@WSt5bZEn', // change it to yours
            'mailtype' => 'html',
            'newline' => "\r\n",
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
            );
        
        $emailMessage = "Hi, \r\n <br/>
To continue with your password reset, visit the following URL:\r\n<br/>
". $pUser->getPasswordResetURL() ."\r\n<br/>
If you did not want to reset your password, just ignore this email.\r\n<br/>
Thanks,\r\n<br/>
Ben - UmpireReporting";
        
        $this->load->library('email', $config);
        $this->email->from('support@umpirereporting.com', 'Umpire Reporting');
        $this->email->to($pUser->getEmailAddress());
        
        $this->email->subject('Umpire Reporting: Password Reset');
        $this->email->message($emailMessage);
        
        if($this->email->send()) {
            return true;
        } else {
            //show_error($this->email->print_debugger());
            return false; 
        }
    }   */

    private function sendPasswordResetEmail(User $pUser) {
        $from = new SendGrid\Email(null, "test@umpirereporting.com");
        $subject = "Hello World from the SendGrid PHP Library!";
        $to = new SendGrid\Email(null, "bbrumm@gmail.com");
        $content = new SendGrid\Content("text/plain", "Hello, Email!");
        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        $apiKey = getenv('SENDGRID_API_KEY');

        $sg = new \SendGrid($apiKey);
        $response = $sg->client->mail()->send()->post($mail);
        //TODO: add check to return OK if the response is OK, otherwise error.
        // _status_code = 202 seems to be ok?
        return true;

    }

}
