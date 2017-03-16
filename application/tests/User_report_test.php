<?php
class User_report_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('User_report');
        $this->userReport = $this->CI->User_report;
        
    }
    
    public function testSimple() {
        $expected = 1;
        $actual = 1;
        $this->assertEquals($expected, $actual);
        
    }
    
    public function testSetReportType()
    {
        $inputReportParameters = array(
            'PDFMode' => FALSE,
            'age' => array('Seniors'),
            'umpireType' => array('Field'),
            'season' => 2016,
            'league' => array('GFL'),
            'region' => 'Geelong',
            'reportName' => 1
        );
    
        $this->userReport->setReportType($inputReportParameters);

        $expectedResult = " ";
        $actualResult = $this->userReport->reportDisplayOptions->getNoDataValue();
        $this->assertEquals($expectedResult, $actualResult);
        
        $expectedResult = "text";
        $actualResult = $this->userReport->reportDisplayOptions->getFirstColumnFormat();
        $this->assertEquals($expectedResult, $actualResult);
        
        $expectedResult = 1;
        $actualResult = $this->userReport->reportDisplayOptions->getColourCells();
        $this->assertEquals($expectedResult, $actualResult);
        
        $expectedResult = 200;
        $actualResult = $this->userReport->reportDisplayOptions->getPDFResolution();
        $this->assertEquals($expectedResult, $actualResult);
        
        $expectedResult = "a3";
        $actualResult = $this->userReport->reportDisplayOptions->getPDFPaperSize();
        $this->assertEquals($expectedResult, $actualResult);
        
        //TODO: Test upper and lower case of these values
        
        $expectedResult = "landscape";
        $actualResult = $this->userReport->reportDisplayOptions->getPDFOrientation();
        $this->assertEquals($expectedResult, $actualResult);
        
        
        $expectedResult = "01 - Umpires and Clubs (2016)";
        $actualResult = $this->userReport->getReportTitle();
        $this->assertEquals($expectedResult, $actualResult);
        
        $expectedResult = 1;
        $actualResult = $this->userReport->getReportId();
        $this->assertEquals($expectedResult, $actualResult);
        
        /*
        $expectedResult = array(
				new ReportGroupingStructure()setFieldName('short_league_name'),
				'club_name'
			);
        $actualResult = $this->userReport->reportDisplayOptions->getColumnGroup();
        $this->assertEquals($expectedResult, $actualResult);
        */
        
        //TODO: Write test for row grouping
        
        $expectedResult = 'Sat 17 Sep 2016, 02:15 PM';
        $actualResult = $this->userReport->reportDisplayOptions->getLastGameDate();
        $this->assertEquals($expectedResult, $actualResult);
    }
    
    public function testGetColumnCountForHeadingCells() {
        $inputReportParameters = array(
            'PDFMode' => FALSE,
            'age' => array('Seniors'),
            'umpireType' => array('Field'),
            'season' => 2016,
            'league' => array('GFL'),
            'region' => 'Geelong',
            'reportName' => 1
        );
        
        $this->userReport->setReportType($inputReportParameters);
        
        $expectedResult = array(
            0 => array (
                0 => array (
                    'label' => 'BFL',
                    'unique_label' => 'BFL',
                    'count' => 10
                    )
            )
            
        );
        $actualResult = $this->userReport->getColumnCountForHeadingCells();
        $this->assertEquals($expectedResult, $actualResult);
        
        
        
    }
    
    
    
}