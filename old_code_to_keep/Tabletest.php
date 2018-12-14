<?php
class TableTest extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Requested_report_model');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Tabletestmodel');
        
        
    }
    
    public function index() {
        $data['test'] = "Test Report";
        $data['css'] = $this->config->item('css');
        //    $this->load->view('templates/header', $data);
        
        $data['umpireTestResultsArray'] = $this->Tabletestmodel->runAllTests();
        //echo $data['umpireTestResultsArray'];
        
        $this->load->view('tabletest', $data);
        
        //$this->load->view('templates/footer');
        
    }
    
    
}
