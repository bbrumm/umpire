<?php
class Report_result extends CI_Model {

    private $resultArray;
    private $columnLabelResultArray;
    private $separateReport;
    //TODO make this private
    public $reportDisplayOptions;
    public $requestedReport;


    public function __construct() {
        $this->load->model('separate_reports/Report_cell');
        $this->load->model('Report_display_options');
    }

    private $reportParamLoader;
    private $reportParameter;
    private $reportTitle;

    public function loadReport(IData_store_matches $pDataStore, Requested_report_model $pRequestedReport) {
        //Load parameters and grouping structire
        $this->loadReportParameters($pRequestedReport);

        $this->setReportTitle($pRequestedReport);

        
        //Create separate report object

        //Load data from database

        //Convert database results to collection

    }

    private function loadReportParameters(IData_store_matches $pDataStore, Requested_report_model $pRequestedReport) {
        $this->reportParamLoader = new Report_param_loader();
        $this->reportParamLoader->loadAllReportParametersForReport($pRequestedReport, $pDataStore);
        $this->reportParameter = $this->reportParamLoader->getReportParameter();
        $this->reportParamLoader->loadAllGroupingStructuresForReport($pRequestedReport, $pDataStore);
    }

    private function setReportTitle($pRequestedReport) {
        $this->reportTitle = str_replace("%seasonYear", $pRequestedReport->getSeason(), $this->reportParameter->getReportTitle());
    }
    
    private function loadReportResultsNew($pDataStore) {
        $$this->separateReport = Report_factory::createReport($this->requestedReport->getReportNumber());
        
        //Create a new report transformer (name TBC?) object
        
        //Call a function on the transformer that calls dataStore->loadReportData,
        //and transforms the data into a set of row and cell objects
        $this->resultArray = array();
        
        //No need to create an output array, as the objects can contain methods to display the output.
        
        
        
    }


    /*
     * =================================================================
     */



    public function loadReportResults($pDataStore) {
        $separateReport = Report_factory::createReport($this->requestedReport->getReportNumber());
        $queryResultArray = $pDataStore->loadReportData($separateReport, $this);

        $this->setResultArray($separateReport, $queryResultArray);

        //Pivot the array so it can be displayed
        $this->setColumnLabelResultArray($pDataStore, $separateReport);
        $this->setResultOutputArray($separateReport);

        //Set to new cell collection
        $this->convertResultArrayToCollection();

        $this->separateReport = $separateReport;
    }



    //TODO: Refactor this now that I am using an array of cells
    public function setResultArray(IReport $pSeparateReport, $pResultArray) {
        $columnLabelArray = $this->populateColumnLabelArray();
        $rowLabelArray = $this->populateRowLabelArray();
        $this->resultArray = $pSeparateReport->pivotQueryArray($pResultArray, $rowLabelArray, $columnLabelArray);
    }

    private function populateColumnLabelArray() {
        $columnLabelArray = array();
        foreach ($this->reportDisplayOptions->getColumnGroup() as $columnGroupItem) {
            $columnLabelArray[] = $columnGroupItem->getFieldName();
        }
        return $columnLabelArray;
    }

    private function populateRowLabelArray() {
        $rowLabelArray = array();
        foreach ($this->reportDisplayOptions->getRowGroup() as $rowGroupItem) {
            $rowLabelArray[] = $rowGroupItem->getFieldName();
        }
        return $rowLabelArray;
    }


    //This function converts the array of report results to a collection of Report_cell objects
    public function convertResultArrayToCollection() {
        $countRows = count($this->resultArray);
        $countColumns = count($this->columnLabelResultArray);
        for($rowCounter = 0; $rowCounter < $countRows; $rowCounter++) {
            for($columnCounter = 0; $columnCounter < $countColumns; $columnCounter++) {
                //Create new Report_cell and add it to the array
                $currentCell = new Report_cell();
                //TODO Add this into a constructor
                $currentCell->setColumnNumber($columnCounter);
                $currentCell->setRowNumber($rowCounter);
                $currentCell->setCellValue($this->checkAndReturnValueFromOutputArray($rowCounter, $columnCounter));
                $this->reportResultCells[] = $currentCell;
            }
        }
        //echo "test";
    }

    private function checkAndReturnValueFromOutputArray($rowCounter, $columnCounter) {
        if (array_key_exists($columnCounter, $this->resultOutputArray[$rowCounter])) {
            return $this->resultOutputArray[$rowCounter][$columnCounter];
        } else {
            return "";
        }
    }


    /*
     * Start Group 2
     */

    //TODO make these private

    
    private $reportColumnFields;
    public $filterParameterUmpireType;
    public $filterParameterAgeGroup;
    public $filterParameterLeague;
    public $filterParameterRegion;


    public function setReportType(IData_store_matches $pDataStore, Requested_report_model $pRequestedReport) {
        //RequestedReport values are set in controllers/report.php->index();
        //$pDataStore = new Database_store_matches();
        $this->reportParamLoader = new Report_param_loader();

        $this->reportParamLoader->loadAllReportParametersForReport($pRequestedReport, $pDataStore);
        $this->reportParameter = $this->reportParamLoader->getReportParameter();
        $this->reportParamLoader->loadAllGroupingStructuresForReport($pRequestedReport, $pDataStore);

        $reportGroupingStructureArray = $this->reportParamLoader->getReportGroupingStructureArray();

        //Removed this because the functionality is already in Report_parameter
        //$this->reportDisplayOptions = Report_display_options::createReportDisplayOptionsNew($this);

        //ReportGroupingStructureArray comes from the database tables
        $this->reportColumnFields = $this->translateRptGrStructureToSimpleArray($reportGroupingStructureArray);
        $this->reportTitle = $this->setReportTitle($pRequestedReport->getSeason());

        $this->requestedReport = $pRequestedReport;

        //Extract the ReportGroupingStructure into separate arrays for columns and rows
        $this->setRowAndColumnGroups($reportGroupingStructureArray);
        $this->filterParameters();
        $this->reportDisplayOptions->setLastGameDate($this->findLastGameDateForSelectedSeason($pDataStore));
    }

    

    private function filterParameters() {
        $this->filterParameterUmpireType = new Report_filter_parameter();
        $this->filterParameterAgeGroup = new Report_filter_parameter();
        $this->filterParameterLeague = new Report_filter_parameter();
        $this->filterParameterRegion = new Report_filter_parameter();
        $this->filterParameterUmpireType->createFilterParameter($this->requestedReport->getUmpireType(), $this->requestedReport->getPDFMode());
        $this->filterParameterAgeGroup->createFilterParameter($this->requestedReport->getAgeGroup(), $this->requestedReport->getPDFMode());
        $this->filterParameterLeague->createFilterParameter($this->requestedReport->getLeague(), $this->requestedReport->getPDFMode());
        $this->filterParameterRegion->createFilterParameter($this->requestedReport->getRegion(), $this->requestedReport->getPDFMode(), true);
    }

    private function setRowAndColumnGroups($pReportGroupingStructureArray) {
        $columnGroupForReport = $this->extractGroupFromGroupingStructure($pReportGroupingStructureArray, 'Column');
        $rowGroupForReport = $this->extractGroupFromGroupingStructure($pReportGroupingStructureArray, 'Row');
        $this->reportDisplayOptions->setColumnGroup($columnGroupForReport);
        $this->reportDisplayOptions->setRowGroup($rowGroupForReport);
    }

    private function translateRptGrStructureToSimpleArray($pReportGroupingStructureArray) {
        $simpleColumnFieldArray =[];
        $countReportGroupingStructureArray = count($pReportGroupingStructureArray);
        for ($i=0; $i < $countReportGroupingStructureArray; $i++) {
            if ($pReportGroupingStructureArray[$i]->getGroupingType() == 'Column') {
                $simpleColumnFieldArray[] = $pReportGroupingStructureArray[$i]->getFieldName();
            }
        }
        return $simpleColumnFieldArray;
    }

    private function extractGroupFromGroupingStructure($pReportGroupingStructureArray, $pGroupingType) {
        //$reportGroupingStructure = new Report_grouping_structure();
        $outputReportGroupingStructure = [];
        $rowCount = count($pReportGroupingStructureArray);
        for($i=0; $i<$rowCount; $i++) {
            if ($pReportGroupingStructureArray[$i]->getGroupingType() == $pGroupingType) {
                $outputReportGroupingStructure[] = $pReportGroupingStructureArray[$i];
            }
        }
        return $outputReportGroupingStructure;
    }

    private function findLastGameDateForSelectedSeason($pDataStore) {
        return $pDataStore->findLastGameDateForSelectedSeason($this->requestedReport);
    }

    /*
     * End Group 2
     */



}
