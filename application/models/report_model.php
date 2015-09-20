<?php
class report_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}
	
	public function get_report() {
		//Only gets the test report at the moment.
		
		$query = $this->db->get('test_report');
		return $query->result_array();
		
	}
	
}