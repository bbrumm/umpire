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
        
        $this->load->view('templates/header');
        
        $data['userArray'] = $userArray;
        $this->load->view('useradmin', $data);
        $this->load->view('templates/footer');
        
    }
    
    
    
    
    
}

?>