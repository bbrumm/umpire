<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class UserAdmin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('report_model');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Useradminmodel');
    }

    function index()
    {
        
        $userAdmin = new Useradminmodel();
        $userArray = $userAdmin->getAllUsers();
        $roleArray = $userAdmin->getRoleArray();
        $subRoleArray = $userAdmin->getSubRoleArray();
        $reportSelectionArray = $userAdmin->getReportArray();
        $regionSelectionArray = $userAdmin->getRegionArray();
        $umpireDisciplineSelectionArray = $userAdmin->getUmpireDisciplineArray();
        $ageGroupSelectionArray = $userAdmin->getAgeGroupArray();
        $leagueSelectionArray = $userAdmin->getLeagueArray();
        
        $this->load->view('templates/header');
        
        $data['userArray'] = $userArray;
        $data['roleArray'] = $roleArray;
        $data['subRoleArray'] = $subRoleArray;
        $data['reportSelectionArray'] = $reportSelectionArray;
        $data['regionSelectionArray'] = $regionSelectionArray;
        $data['umpireDisciplineSelectionArray'] = $umpireDisciplineSelectionArray;
        $data['ageGroupSelectionArray'] = $ageGroupSelectionArray;
        $data['leagueSelectionArray'] = $leagueSelectionArray;
        $this->load->view('useradmin', $data);
        $this->load->view('templates/footer');
        
    }
    
    
    
    
    
}

?>