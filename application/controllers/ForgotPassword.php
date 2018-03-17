<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ForgotPassword extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('user');
        $this->load->helper('string');
        
    }
    
    function index()
    {
        $this->load->helper(array('form'));
        
        $this->load->view('templates/header');
        $this->load->view('password_reset');    
        $this->load->view('templates/footer');
        
    }
    
    public function submitChangePasswordForm() {
        //Perform checks and send confirmation email
        $username = $this->security->xss_clean($this->input->post('username'));
        $emailAddress = $this->security->xss_clean($this->input->post('emailAddress'));
        $sendStatusInfo = $this->validateUserAndSendEmail($username, $emailAddress);
        
        if ($sendStatusInfo['status'] == "sent") {
            //Show page
            $this->showPasswordResetNextStepsPage($sendStatusInfo);
        } else {
            $this->showPasswordResetPage($sendStatusInfo);
            
        }
    }
    
    private function showPasswordResetNextStepsPage($sendStatusInfo) {
        $data['sendStatus'] = $sendStatusInfo['status'];
        $data['sendStatusMessage'] = $sendStatusInfo['message'];
        $data['passwordResetURL'] = $sendStatusInfo['passwordResetURL'];
        
        $this->load->view('templates/header');
        $this->load->view('password_reset_next', $data);
        $this->load->view('templates/footer');
    }
    
    private function showPasswordResetPage($sendStatusInfo) {
        $data['sendStatus'] = $sendStatusInfo['status'];
        $data['sendStatusMessage'] = $sendStatusInfo['message'];
        
        $this->load->helper(array('form'));
        $this->load->view('templates/header');
        $this->load->view('password_reset', $data);
        $this->load->view('templates/footer');
    }
    
    private function validateUserAndSendEmail($pUserName, $pEmailAddress) {
        $umpireUser = new User();
        $umpireUser->setUsername($pUserName);
        $umpireUser->setEmailAddress($pEmailAddress);
        
        $data['activation_id'] = random_string('alnum',15);
        $data['request_datetime'] = date('Y-m-d H:i:s');
        $data['client_ip'] = $this->input->ip_address();
        $data['username_entered'] = $umpireUser->getUsername();
        $data['email_address_entered'] = $umpireUser->getEmailAddress();

        $logRequest = $umpireUser->logPasswordResetRequest($data);
        
        //Check user data entered: user exists, email matches user
        if($umpireUser->checkUserExistsForReset()) {
            
            $encoded_email = urlencode($pEmailAddress);
            
            if($logRequest) {
                $umpireUser->getUserFromUsername($pUserName);
                $umpireUser->setPasswordResetURL(base_url() . "index.php/ResetPasswordEntry/load/" . $data['activation_id']);
                
                $umpireUser->storeActivationID($data['activation_id']);
                
                $sendStatus = $this->sendPasswordResetEmail($umpireUser);
                
                if($sendStatus){
                    $sendStatusInfo['status'] = "sent";
                    $sendStatusInfo['message'] = 
                        "Please check your email for a link to reset your password.";
                    $sendStatusInfo['passwordResetURL'] = $umpireUser->getPasswordResetURL();
                } else {
                    $sendStatusInfo['status'] = "not sent";
                    $sendStatusInfo['message'] = 
                        "The email has failed to send. Please check the data you have entered and try again, or contact support.";
                }
            } else {
                $sendStatusInfo['status'] = "unable";
                $sendStatusInfo['message'] = 
                    "There was an error while attempting to generate the password reset request. Please try again or contact support.";
            }
               
        } else {
            $sendStatusInfo['status'] = "incorrect";
            $sendStatusInfo['message'] =
                "Username or email address not found. Please try again or contact support.";
        }
        
        return $sendStatusInfo;
    }
    
    private function sendPasswordResetEmail($pUser) {
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
        $this->email->to('brummthecar@gmail.com');
        
        $this->email->subject('Umpire Reporting: Password Reset');
        $this->email->message($emailMessage);
        
        if($this->email->send()) {
            return true;
        } else {
            //show_error($this->email->print_debugger());
            return false; 
        }
        
        
        return true;
    }   
}
?>