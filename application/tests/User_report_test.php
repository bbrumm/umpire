<?php
class User_report_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('User_report');
        $this->userReport = $this->CI->User_report;
        
    }
    
    /**
     * @dataProvider setReportTypeProvider
     */
    public function testSetReportType($inputReportParameters,
        $expectedNoDataValue, $expectedFirstColumnFormat, $expectedColourCells, 
        $expectedPDFResolution, $expectedPDFPaperSize, $expectedPDFOrientation,
        $expectedReportTitle, $expectedReportID, $expectedLastGameDate)
    {
    
        $this->userReport->setReportType($inputReportParameters);
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
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors'),
                    'umpireType' => array('Field'),
                    'season' => 2016,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportName' => 1
                ),
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
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2015,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportName' => 2
                ),
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
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2000,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportName' => 3
                ),
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
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2017,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportName' => 4
                ),
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
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2017,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportName' => 5
                ),
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
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2312,
                    'league' => array('GFL'),
                    'region' => 'Geelong',
                    'reportName' => 6
                ),
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
        $inputReportParameters = array(
            'PDFMode' => FALSE,
            'age' => array('Seniors'),
            'umpireType' => array('Field'),
            'season' => 2016,
            'league' => array('GFL'),
            'region' => 'Geelong',
            'reportName' => 1
        );
        
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
        
        $this->userReport->setReportType($inputReportParameters);
        
        $this->userReport->setColumnLabelResultArray($columnLabelArray);
        
        $expectedResult = array(
            0 => array (
                0 => array (
                    'label' => 'BFL',
                    'unique label' => 'BFL',
                    'count' => 5
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
                )
            )
            
        );
        $actualResult = $this->userReport->getColumnCountForHeadingCells();
        $this->assertEquals($expectedResult, $actualResult);
    }
    
    /**
     * @dataProvider inputReportParameterProvider
     */
    public function testConvertParametersToSQLReadyValues($inputReportParameters,
        $expectedUmpireType, $expectedLeague, $expectedAgeGroup, $expectedRegion) {
        
        $this->userReport->convertParametersToSQLReadyValues($inputReportParameters);
    
        //Umpire Type
        $actualResult = $this->userReport->getUmpireTypeSQLValues();
        $this->assertEquals($expectedUmpireType, $actualResult);
    
        //League
        $actualResult = $this->userReport->getLeagueSQLValues();
        $this->assertEquals($expectedLeague, $actualResult);
    
        //Age Group
        $actualResult = $this->userReport->getAgeGroupSQLValues();
        $this->assertEquals($expectedAgeGroup, $actualResult);
    
        //Region
        $actualResult = $this->userReport->getRegionSQLValues();
        $this->assertEquals($expectedRegion, $actualResult);
    }
    
    public function inputReportParameterProvider() {
        return array (
            array(
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors'),
                    'umpireType' => array('Field', 'Goal'),
                    'season' => 2016,
                    'league' => array('GFL', 'ABC', 'DEF', '12434', 'AJBVJHFBVDFVER JERI n'),
                    'region' => 'Geelong',
                    'reportName' => 1
                ),
                'expectedUmpireType' => "'Field','Goal'",
                'expectedLeague' => "'GFL','ABC','DEF','12434','AJBVJHFBVDFVER JERI n'",
                'expectedAgeGroup' => "'Seniors'",
                'expectedRegion' => "'Geelong'"
            ),
            array(
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2016,
                    'league' => array('GFL', 'A12'),
                    'region' => 'COLAC',
                    'reportName' => 2
                ),
                'expectedUmpireType' => "'Field'",
                'expectedLeague' => "'GFL','A12'",
                'expectedAgeGroup' => "'Seniors','Reserves'",
                'expectedRegion' => "'COLAC'"
                
            )
        );
    }
    
    public function testConvertParametersToSQLReadyValuesPDF() {
        $inputReportParameters = array(
            'PDFMode' => TRUE,
            'age' => 'Seniors,Senior',
            'umpireType' => 'Field,Goal,Umpires,ALL',
            'season' => 2016,
            'league' => 'GFL,BF,GFL,GFL',
            'region' => 'GeelongASC',
            'reportName' => 1
        );
    
        $this->userReport->convertParametersToSQLReadyValues($inputReportParameters);
    
        //Umpire Type
        $expectedResult = "'Field','Goal','Umpires','ALL'";
        $actualResult = $this->userReport->getUmpireTypeSQLValues();
        $this->assertEquals($expectedResult, $actualResult);
    
    
        //League
        $expectedResult = "'GFL','BF','GFL','GFL'";
        $actualResult = $this->userReport->getLeagueSQLValues();
        $this->assertEquals($expectedResult, $actualResult);
    
        //Age Group
        $expectedResult = "'Seniors','Senior'";
        $actualResult = $this->userReport->getAgeGroupSQLValues();
        $this->assertEquals($expectedResult, $actualResult);
    
        //Region
        $expectedResult = "'GeelongASC'";
        $actualResult = $this->userReport->getRegionSQLValues();
        $this->assertEquals($expectedResult, $actualResult);
    }
    
    /**
     * @dataProvider inputReportParameterDisplayProvider
     */
    public function testConvertParametersToDisplayValues($inputReportParameters,
        $expectedUmpireType, $expectedLeague, $expectedAgeGroup) {
        
        /*$inputReportParameters = array(
            'PDFMode' => FALSE,
            'age' => array('Seniors'),
            'umpireType' => array('Field', 'Goal'),
            'season' => 2016,
            'league' => array('GFL', 'ABC', 'DEF', '12434', 'AJBVJHFBVDFVER JERI n'),
            'region' => 'Geelong',
            'reportName' => 1
        );
        */
        
        $this->userReport->convertParametersToDisplayValues($inputReportParameters);
        
        //Umpire Type
        //$expectedResult = "Field, Goal";
        $actualResult = $this->userReport->getUmpireTypeDisplayValues();
        $this->assertEquals($expectedUmpireType, $actualResult);
        
        //League
        //$expectedResult = "GFL, ABC, DEF, 12434, AJBVJHFBVDFVER JERI n";
        $actualResult = $this->userReport->getLeagueDisplayValues();
        $this->assertEquals($expectedLeague, $actualResult);
        
        //Age Group
        //$expectedResult = "Seniors";
        $actualResult = $this->userReport->getAgeGroupDisplayValues();
        $this->assertEquals($expectedAgeGroup, $actualResult);
        
    }
    
    public function inputReportParameterDisplayProvider() {
        return array (
            array(
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors'),
                    'umpireType' => array('Field', 'Goal'),
                    'season' => 2016,
                    'league' => array('GFL', 'ABC', 'DEF', '12434', 'AJBVJHFBVDFVER JERI n'),
                    'region' => 'Geelong',
                    'reportName' => 1
                ),
                'expectedUmpireType' => "Field, Goal",
                'expectedLeague' => "GFL, ABC, DEF, 12434, AJBVJHFBVDFVER JERI n",
                'expectedAgeGroup' => "Seniors",
                'expectedRegion' => "Geelong"
            ),
            array(
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors', 'Reserves'),
                    'umpireType' => array('Field'),
                    'season' => 2016,
                    'league' => array('GFL', 'A12'),
                    'region' => 'COLAC',
                    'reportName' => 2
                ),
                'expectedUmpireType' => "Field",
                'expectedLeague' => "GFL, A12",
                'expectedAgeGroup' => "Seniors, Reserves"
    
            ),
            array(
                array(
                    'PDFMode' => FALSE,
                    'age' => array('Seniors', 'Reserves', 'U12', 'U15'),
                    'umpireType' => array('Fie\'ld', 'GO'),
                    'season' => 2016,
                    'league' => array('12'),
                    'region' => 'A',
                    'reportName' => 2
                ),
                'expectedUmpireType' => "Fie'ld, GO",
                'expectedLeague' => "12",
                'expectedAgeGroup' => "Seniors, Reserves, U12, U15"
    
            )
        );
    }
    
}