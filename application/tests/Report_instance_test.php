<?php
class Report_instance_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Report_instance');
        $this->userReport = $this->CI->Report_instance;
        $this->CI->load->model('Requested_report_model');
        
    }
    
    /**
     * @dataProvider setReportTypeProvider
     */
    public function testSetReportType(Requested_report_model $pRequestedReport,
        $expectedNoDataValue, $expectedFirstColumnFormat, $expectedColourCells, 
        $expectedPDFResolution, $expectedPDFPaperSize, $expectedPDFOrientation,
        $expectedReportTitle, $expectedReportID, $expectedLastGameDate)
    {
    
        $this->userReport->setReportType($pRequestedReport);
        $actualResult = $this->userReport->reportDisplayOptions->getNoDataValue();
        $this->assertEquals($expectedNoDataValue, $actualResult);
        
        $actualResult = $this->userReport->reportDisplayOptions->getFirstColumnFormat();
        $this->assertEquals($expectedFirstColumnFormat, $actualResult);
        
        $actualResult = $this->userReport->reportDisplayOptions->getColourCells();
        $this->assertEquals($expectedColourCells, $actualResult);
        
        $actualResult = $this->userReport->reportDisplayOptions->getPDFResolution();
        $this->assertEquals($expectedPDFResolution, $actualResult);
        
        $actualResult = $this->userReport->reportDisplayOptions->getPDFPaperSize();
        $this->assertEquals($expectedPDFPaperSize, $actualResult);
        
        $actualResult = $this->userReport->reportDisplayOptions->getPDFOrientation();
        $this->assertEquals($expectedPDFOrientation, $actualResult);
        
        $actualResult = $this->userReport->getReportTitle();
        $this->assertEquals($expectedReportTitle, $actualResult);
        
        $actualResult = $this->userReport->requestedReport->getReportNumber();
        $this->assertEquals($expectedReportID, $actualResult);
        
        /*
        $expectedResult = array(
				new ReportGroupingStructure()setFieldName('short_league_name'),
				'club_name'
			);
        $actualResult = $this->userReport->reportDisplayOptions->getColumnGroup();
        $this->assertEquals($expectedResult, $actualResult);
        */

        $actualResult = $this->userReport->reportDisplayOptions->getLastGameDate();
        $this->assertEquals($expectedLastGameDate, $actualResult);
    }
    
    public function setReportTypeProvider() {
        return array(
            array(
                Requested_report_model::withValues(array(
                    'pdfMode' => FALSE,
                    'ageGroup' => array('Seniors'),
                    'umpireType' => array('Field'),
                    'season' => 2016,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportNumber' => 1
                )),
                'expectedNoDataValue' => " ",
                'expectedFirstColumnFormat' => "text",
                'expectedColourCells' => 1,
                'expectedPDFResolution' => 200,
                'expectedPDFPaperSize' => "a3",
                'expectedPDFOrientation' => "landscape",
                'expectedReportTitle' => "01 - Umpires and Clubs (2016)",
                'expectedReportId' => 1,
                'expectedLastGameDate' => 'Sat 17 Sep 2016, 02:15 PM'
            ),
            array(
                Requested_report_model::withValues(array(
                    'pdfMode' => FALSE,
                    'ageGroup' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2015,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportNumber' => 2
                )),
                'expectedNoDataValue' => " ",
                'expectedFirstColumnFormat' => "text",
                'expectedColourCells' => 0,
                'expectedPDFResolution' => 200,
                'expectedPDFPaperSize' => "a3",
                'expectedPDFOrientation' => "portrait",
                'expectedReportTitle' => "02 - Umpire Names by League (2015)",
                'expectedReportId' => 2,
                'expectedLastGameDate' => 'Sat 19 Sep 2015, 02:10 PM'
            ),
            array(
                Requested_report_model::withValues(array(
                    'pdfMode' => FALSE,
                    'ageGroup' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2000,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportNumber' => 3
                )),
                'expectedNoDataValue' => " ",
                'expectedFirstColumnFormat' => "date",
                'expectedColourCells' => 0,
                'expectedPDFResolution' => 200,
                'expectedPDFPaperSize' => "a3",
                'expectedPDFOrientation' => "landscape",
                'expectedReportTitle' => "03 - Summary by Week (Matches Where No Umpires Are Recorded) (2000)",
                'expectedReportId' => 3,
                'expectedLastGameDate' => NULL
            ),
            array(
                Requested_report_model::withValues(array(
                    'pdfMode' => FALSE,
                    'ageGroup' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2017,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportNumber' => 4
                )),
                'expectedNoDataValue' => " ",
                'expectedFirstColumnFormat' => "text",
                'expectedColourCells' => 0,
                'expectedPDFResolution' => 200,
                'expectedPDFPaperSize' => "a3",
                'expectedPDFOrientation' => "landscape",
                'expectedReportTitle' => "04 - Summary by Club (Matches Where No Umpires Are Recorded) (2017)",
                'expectedReportId' => 4,
                'expectedLastGameDate' => NULL
            ),
            array(
                Requested_report_model::withValues(array(
                    'pdfMode' => FALSE,
                    'ageGroup' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2017,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportNumber' => 5
                )),
                'expectedNoDataValue' => 0,
                'expectedFirstColumnFormat' => "text",
                'expectedColourCells' => 0,
                'expectedPDFResolution' => 100,
                'expectedPDFPaperSize' => "a3",
                'expectedPDFOrientation' => "landscape",
                'expectedReportTitle' => "05 - Games with Zero Umpires For Each League (2017)",
                'expectedReportId' => 5,
                'expectedLastGameDate' => NULL
            ),
            array(
                Requested_report_model::withValues(array(
                    'pdfMode' => FALSE,
                    'ageGroup' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2312,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportNumber' => 6
                )),
                'expectedNoDataValue' => " ",
                'expectedFirstColumnFormat' => "text",
                'expectedColourCells' => 1,
                'expectedPDFResolution' => 200,
                'expectedPDFPaperSize' => "a3",
                'expectedPDFOrientation' => "landscape",
                'expectedReportTitle' => "06 - Umpire Pairing (2312)",
                'expectedReportId' => 6,
                'expectedLastGameDate' => NULL
            )
        );
        
        
    }

    
    
    
}