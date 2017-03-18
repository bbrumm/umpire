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
        
        //TODO: Test upper and lower case of these values
        $actualResult = $this->userReport->reportDisplayOptions->getPDFOrientation();
        $this->assertEquals($expectedPDFOrientation, $actualResult);
        
        $actualResult = $this->userReport->getReportTitle();
        $this->assertEquals($expectedReportTitle, $actualResult);
        
        $actualResult = $this->userReport->getReportId();
        $this->assertEquals($expectedReportID, $actualResult);
        
        /*
        $expectedResult = array(
				new ReportGroupingStructure()setFieldName('short_league_name'),
				'club_name'
			);
        $actualResult = $this->userReport->reportDisplayOptions->getColumnGroup();
        $this->assertEquals($expectedResult, $actualResult);
        */
        
        //TODO: Write test for row grouping

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
    
    public function testGetColumnCountForHeadingCells() {
        
        $inputRequestedReport = new Requested_report_model();
        $inputRequestedReport->setAgeGroup(array('Seniors'));
        $inputRequestedReport->setUmpireType(array('Field'));
        $inputRequestedReport->setSeason(2016);
        $inputRequestedReport->setLeague(array('BFL'));
        $inputRequestedReport->setRegion('Geelong');
        $inputRequestedReport->setReportNumber(1);
        $inputRequestedReport->setPDFMode(FALSE);
        
        $columnLabelArray = array(
            0 => Array (
                'column_name' => 'BFL|Anglesea',
                'report_column_id' => 1,
                'short_league_name' => 'BFL',
                'club_name' => 'Anglesea'
            ),
            1 => Array (
                'column_name' => 'BFL|Barwon_Heads',
                'report_column_id' => 2,
                'short_league_name' => 'BFL',
                'club_name' => 'Barwon Heads'
            ),
            2 => Array (
                'column_name' => 'BFL|Drysdale',
                'report_column_id' => 3,
                'short_league_name' => 'BFL',
                'club_name' => 'Drysdale'
            ),
            3 => Array (
                'column_name' => 'BFL|Geelong_Amateur',
                'report_column_id' => 4,
                'short_league_name' => 'BFL',
                'club_name' => 'Geelong Amateur'
            ),
            4 => Array (
                'column_name' => 'BFL|Modewarre',
                'report_column_id' => 5,
                'short_league_name' => 'BFL',
                'club_name' => 'Modewarre'
            )
        );
        
        $this->userReport->setReportType($inputRequestedReport);
        
        $this->userReport->setColumnLabelResultArray($columnLabelArray);
        
        $expectedResult = array(
            0 => array (
                0 => array (
                    'label' => 'BFL',
                    'unique label' => 'BFL',
                    'count' => 10
                    )
            ),
            1 => array (
                0 => array (
                    'label' => 'Anglesea',
                    'unique label' => 'BFL|Anglesea',
                    'count' => 1
                ),
                1 => array (
                    'label' => 'Barwon Heads',
                    'unique label' => 'BFL|Barwon Heads',
                    'count' => 1
                ),
                2 => array (
                    'label' => 'Drysdale',
                    'unique label' => 'BFL|Drysdale',
                    'count' => 1
                ),
                3 => array (
                    'label' => 'Geelong Amateur',
                    'unique label' => 'BFL|Geelong Amateur',
                    'count' => 1
                ),
                4 => array (
                    'label' => 'Modewarre',
                    'unique label' => 'BFL|Modewarre',
                    'count' => 1
                ),
                5 => array (
                    'label' => 'Newcomb',
                    'unique label' => 'BFL|Newcomb',
                    'count' => 1
                ),
                6 => array (
                    'label' => 'Ocean Grove',
                    'unique label' => 'BFL|Ocean Grove',
                    'count' => 1
                ),
                7 => array (
                    'label' => 'Portarlington',
                    'unique label' => 'BFL|Portarlington',
                    'count' => 1
                ),
                8 => array (
                    'label' => 'Queenscliff',
                    'unique label' => 'BFL|Queenscliff',
                    'count' => 1
                ),
                9 => array (
                    'label' => 'Torquay',
                    'unique label' => 'BFL|Torquay',
                    'count' => 1
                )
            )
            
        );
        $actualResult = $this->userReport->getColumnCountForHeadingCells();
        $this->assertEquals($expectedResult, $actualResult);
    }
    
    
    
}