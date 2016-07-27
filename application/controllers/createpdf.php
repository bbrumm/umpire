<?php

class CreatePDF extends CI_Controller {
	
	function __construct()
	 {
	   parent::__construct();
	   $this->load->model('user','',TRUE);
	   $this->load->model('report_model');
		$this->load->helper('url_helper');
		$this->load->helper('cell_formatting_helper');
	 }
	 
	 function writeToFile($outputText) {
	     $file = 'testOutput.txt';
	     // Open the file to get existing content
	     $current = file_get_contents($file);
	     // Append a new person to the file
	     $current .= $outputText . "\n";
	     // Write the contents back to the file
	     file_put_contents($file, $current);
	     
	 }
	
	function pdf() {
	    $debugMode = $this->config->item('debug_mode');
	    
	    $startTime = time();
	    if ($debugMode) {
	        echo "POST in createpdf.pdf:<pre>";
	        print_r($_POST);
	        echo "</pre>";
	        
	    }
		//$this->load->helper('pdf_helper');
		$reportParameters = array(
			'reportName' => $_POST['reportName'], 
			'season' => $_POST['season'], 
			'age' => $_POST['age'], 
			'umpireType' => $_POST['umpireType'], 
			'league' => $_POST['league'], 
			'region' => $_POST['region']);
		
		$this->writeToFile("time 01: " . time());
		$startTime = time();
		
		$data['loadedReportItem'] = $this->report_model->get_report($reportParameters);
		$data['title'] = 'Test Report';
		$data['PDFLayout'] = TRUE;

		$this->load->helper(array('dompdf', 'file'));
		/*
		echo "loadedReportItem in createpdf:<pre>";
		print_r($data['loadedReportItem']);
		echo "</pre>";
        */
		
		// page info here, db calls, etc.     
		
		$html = $this->load->view('templates/header', $data, TRUE);

		$html .= $this->load->view('report/pdf_report_view', $data, TRUE);
		
		$html .= $this->load->view('templates/footer', $data, TRUE);

		//Save To File (TRUE), or Output to Window (FALSE).
		$saveToFile = TRUE;
		
		//echo "RES (". $data['loadedReportItem']->reportDisplayOptions->getPDFResolution() .")";
		
		if ($saveToFile) {
    		pdf_create($html, 'pdf_report_view', $saveToFile, $data['loadedReportItem']->reportDisplayOptions->getPDFResolution());
		} else {
		    echo $html;
		}

		//Instead, try saving to HTML then using that HTML
		//$html = create_html( $doc_id );
		/*
		$filename_html = 'testspeed.html';
		$filename_pdf = 'testspeed.pdf';
		file_put_contents( $filename_html , $html );
		//exec( 'dompdf/dompdf.php ' . $filename_html );
		pdf_create($filename_html, 'report_method_2', TRUE);
		*/
		//echo "done";
		//if ( file_exists( $filename_pdf ) ) {
		//    $filenames[] = $filename_pdf;
		//}
		//print_R($filenames);
		//exec('gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE="merged_pdf.pdf" -dBATCH ' . implode( ' ' , $filenames ) );
		  
		//echo "time 07: " . time() - $startTime . "<BR />";
		$this->writeToFile("time 07: " . time());
		$startTime = time();
		 
		//
		
	}
	/*
	function pdfUsingTCPDF() {
	    
	    $this->load->helper('pdf_helper');

	    $this->writeToFile("pdfUsingTCPDF");
	    
	    $reportParameters = array(
	        'reportName' => $_POST['reportName'],
	        'season' => $_POST['season'],
	        'age' => $_POST['age'],
	        'umpireType' => $_POST['umpireType'],
	        'league' => $_POST['league']);
	    
	    $data['loadedReportItem'] = $this->report_model->get_report($reportParameters);
	    $data['title'] = 'Test Report';
	    $data['PDFLayout'] = TRUE;

	    
	    $this->load->view('templates/header', $data, TRUE);
	    $this->load->view('pdfreport', $data);
	    
	}
	*/

}
?>