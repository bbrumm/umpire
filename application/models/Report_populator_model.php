<?php
class Report_populator_model extends CI_Model {
	public function __construct() {
		$this->load->database();
		$this->load->model('Report_instance');
		$this->load->library('Array_library');
		$this->load->model('Requested_report_model');
        $this->load->model('data_store/Database_store_report_param');
        $this->load->model('data_store/Database_store_matches');
		$this->load->library('Debug_library');
	}

	//TODO Remove this FeatureFlag once done
	//private $useNewReport = false;

	public function getReport(Requested_report_model $requestedReport) {
	    /*
	    if($this->useNewReport) {
            return $this->getReportNew($requestedReport);
        } else {
	        return $this->getReportOld($requestedReport);
        }
	    */
        return $this->getReportModified($requestedReport);

    }

    /*
	public function getReportOld(Requested_report_model $requestedReport) {
	    $requestedReport->setPDFMode(isset($_POST['PDFSubmitted']));
	    
		$reportToDisplay = new Report_instance();
		$dataStore = new Database_store_matches();
		$dataStoreReportParam = new Database_store_report_param();
		$reportToDisplay->setReportType($dataStoreReportParam, $dataStore, $requestedReport);
    */
		
		 /* To get the column labels, currently I get the query data for the report and then translate that.
		 * Is that the best way to go? Or should I use a different method of getting the column labels?
		 * If I keep doing it the same way, then I'll need to convert the report results to a collection
		 * of objects first, then convert the label arrays to objects.
		 * The function to get the report data should pass in the report parameters, and receive a collection
		 * of objects.
		 *  
		 */

/*
		$reportToDisplay->loadReportResults($dataStore);
		
		return $reportToDisplay;

	}*/
/*
	public function getReportNew(Requested_report_model $requestedReport) {
        $requestedReport->setPDFMode(isset($_POST['PDFSubmitted']));

        $reportResult = new Report_resultTempinprogress();
        $dataStore = new Database_store_matches();

        $reportResult->loadReport($dataStore, $requestedReport);

        return $reportResult;
    }
*/

    public function getReportModified(Requested_report_model $requestedReport) {
        $requestedReport->setPDFMode(isset($_POST['PDFSubmitted']));

        $reportToDisplay = new Report_instance();
        $dataStore = new Database_store_matches();
        $dataStoreReportParam = new Database_store_report_param();
        $reportToDisplay->setReportType($dataStoreReportParam, $dataStore, $requestedReport);
        $reportToDisplay->loadReportResults($dataStore);

        return $reportToDisplay;

    }
}
