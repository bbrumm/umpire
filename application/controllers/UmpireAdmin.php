<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class UmpireAdmin extends CI_Controller
{

    function __construct() {
        parent::__construct();

        //$this->load->model('Report_instance');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Umpireadminmodel');
        $this->load->model('data_store/Database_store_umpire_admin');
        $this->load->library('Debug_library');
    }

    public function loadPage($pUserAddedMessage = "") {
        $umpireAdmin = new Umpireadminmodel();
        $dataStore = new Database_store_umpire_admin();
        $umpireArray = $umpireAdmin->getAllUmpiresAndValues($dataStore);
        $data = array();
        $data['umpireArray'] = $umpireArray;
        $data['dataUpdatedMessage'] = "Data updated.";
        $data['userAddedMessage'] = $pUserAddedMessage;

        $this->load->view('templates/header');
        $this->load->view('umpireadmin', $data);
        $this->load->view('templates/footer');


    }

    public function updateUmpireGamesPlayed() {
        /*
        Update all numbers from the screen.
        No need to worry about adding a dirty flag, as this is a step that will not happen very often.
        I also need to log when these changes were made, and by who,
        and what the before and after values were, just in case we need to track it.
        */
        $dataStore = new Database_store_umpire_admin();
        $umpireAdmin = new Umpireadminmodel();
        $umpireUpdateSuccess = $umpireAdmin->updateUmpireGameValues($dataStore, $_POST);

        if ($umpireUpdateSuccess) {
            $umpireUpdateSuccessMessage= "Data successfully updated.";
        } else {
            $umpireUpdateSuccessMessage= "Data not updated.";
        }
        $this->loadPage($umpireUpdateSuccessMessage);

    }


}
