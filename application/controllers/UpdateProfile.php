<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
/*
* @property Object session
* @property Object security
* @property Object input
*/
class UpdateProfile extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->model('User');
        $this->load->model('useradmin/User_maintenance_model');
        $this->load->model('useradmin/User_permission_loader_model');
        $this->load->model('data_store/Database_store_user');
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

        $data = array();
        $data['username'] = $umpireUser->getUsername();
        $data['email_address'] = $umpireUser->getEmailAddress();
        $data['userAddedMessage'] = $pUserAddedMessage;
        $data['messageIsSuccess'] = $pMessageIsSuccess;

        $this->load->view('templates/header');
        $this->load->view('updateprofile', $data);
        $this->load->view('templates/footer');
    }


    public function updateEmail() {
        $userMaintenance = new User_maintenance_model();
        $dbStore = new Database_store_user();
        $userName = $_POST['username'];

        $newEmail= $this->security->xss_clean($this->input->post('email_address'));


        if (strlen($newEmail) == 0) {
            $statusMessage = "Email address not updated. Length must be greater than 0.";
            $this->loadPage($statusMessage, FALSE);
        } else {
            $umpireUser = new User();
            $umpireUser->setUsername($userName);
            $umpireUser->setEmailAddress($newEmail);
            $userMaintenance->updateEmailAddress($dbStore, $umpireUser);
            $statusMessage = "Email address updated successfully.";
            $this->loadPage($statusMessage, TRUE);
        }
    }

    private function lookupUserData(User $pUser) {
        $userPermissionLoader = new User_permission_loader_model();
        $dbStore = new Database_store_user();

        $foundUser = $userPermissionLoader->getUserFromUsername($dbStore, $pUser->getUsername());

        return $foundUser;
    }

}
