<?php
class Pages extends CI_Controller {
	
	public function view($page = 'home')
	{
			//echo "CHECK (APPPATH.'/views/pages/'.$page.'.php')";
			if ( ! file_exists(APPPATH.'/views/pages/'.$page.'.php'))
			{
				//Page does not exist
				show_404();
			}
			$data['title'] = ucfirst($page); //Capitalise the first letter
			$this->load->view('templates/header', $data);
			$this->load->view('pages/'.$page, $data);
			$this->load->view('templates/footer', $data);
				
	}
}
	