<?php
class Report_instance_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Report_instance');
        $this->obj = $this->CI->Report_instance;
    }

    public function test_SetReportType() {
        // $pReportNumber, $pSeason, $pRegion, $pAgeGroup, $pUmpireType, $pLeague, $pPDFMode
        $reportNumber = 1;
        $season = 2018;
        $region = 'Geelong';
        $ageGroup = array('Under 18');
        $umpireType = array('Senior');
        $league = array('GFL');
        $pdfMode = false;

        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            $reportNumber, $season, $region, $ageGroup, $umpireType, $league, $pdfMode
        );
        $dataStore = new Array_store_matches();
        $dataStoreReportParam = new Array_store_report_param();

        $this->obj->setReportType($dataStoreReportParam, $dataStore, $requestedReport);
        $expectedReportTitle = "Random Title " . $season;
        $actualReportTitle = $this->obj->getReportTitle();
        $this->assertEquals($expectedReportTitle, $actualReportTitle);

        $expectedColumnGroupSet = true;
        $actualColumnGroupSet = !empty($this->obj->getDisplayOptions()->getColumnGroup());
        $this->assertEquals($expectedColumnGroupSet, $actualColumnGroupSet);

        $expectedReportColumnFieldsSet = true;
        $actualReportColumnFieldsSet = !empty($this->obj->getReportColumnFields());
        $this->assertEquals($expectedReportColumnFieldsSet, $actualReportColumnFieldsSet);
    }

    public function test_LoadReportResults() {
        // $pReportNumber, $pSeason, $pRegion, $pAgeGroup, $pUmpireType, $pLeague, $pPDFMode
        $reportNumber = 1;
        $season = 2018;
        $region = 'Geelong';
        $ageGroup = array('Under 18');
        $umpireType = array('Senior');
        $league = array('GFL');
        $pdfMode = false;

        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            $reportNumber, $season, $region, $ageGroup, $umpireType, $league, $pdfMode
        );
        $dataStore = new Array_store_matches();
        $dataStoreReportParam = new Array_store_report_param();

        $this->obj->setReportType($dataStoreReportParam, $dataStore, $requestedReport);
        $this->obj->loadReportResults($dataStore);

        $this->assertEquals(1, 1);
        //TODO fix this test
        $expectedColumnLabelResultArraySet = true;
        $columnLabelResultArray = $this->obj->getColumnLabelResultArray();
        //echo "getColumnLabelResultArray: <pre>" . print_r($columnLabelResultArray) . "</pre>";
        //echo "getColumnLabelResultArray empty:" . isset($columnLabelResultArray);
        $actualColumnLabelResultArraySet = !empty($columnLabelResultArray);
        $this->assertEquals($expectedColumnLabelResultArraySet, $actualColumnLabelResultArraySet);

        $expectedResultOutputArraySet = true;
        $resultOutputArray = $this->obj->getResultOutputArray();
        //echo "<pre>" . print_r($resultOutputArray) . "</pre>";
        //echo "resultOutputArray count: " . count($resultOutputArray);
        $actualResultOutputArraySet = !empty($resultOutputArray);
        $this->assertEquals($expectedResultOutputArraySet, $actualResultOutputArraySet);


        /*$expectedResultArray =
            Array (
                'john' => Array (
                    Array ('short_league_name' => 'GFL', 'match_count' => 2, 'club_name' => 'Geelong'),
                    Array ('short_league_name' => 'BFL', 'match_count' => 1, 'club_name' => 'Hawthorn')
                ),
                'sue' => Array (
                    Array ('short_league_name' => 'GFL','match_count' => 5, 'club_name' => 'Melbourne'),
                    Array ('short_league_name' => 'BFL','match_count' => 7, 'club_name' => 'Hawthorn')
                ),
                'mark' => Array (
                    Array ('short_league_name' => 'BFL','match_count' => 7, 'club_name' => 'Hawthorn')
                ),
                'matt' => Array (
                    Array ('short_league_name' => 'BFL','match_count' => 7, 'club_name' => 'Essendon'),
                    Array ('short_league_name' => 'GFL','match_count' => 7, 'club_name' => 'Carlton')
                )
            );
        */


        //TODO: Change this to be an array of Report_cell not array of arrays
        /*
         *
         *
         * Array (
            0 => Array (
                0 => Report_cell Object (...)
                1 => Report_cell Object (...)
         */
        $expectedResultArray =
            Array (
                0 => Array (
                    Report_cell::createNewReportCellWithHeaders('john', 'Name', null),
                    Report_cell::createNewReportCellWithHeaders(2, 'GFL', 'Geelong'),
                    Report_cell::createNewReportCellWithHeaders(1, 'BFL', 'Hawthorn')
                ),
                1 => Array (
                    Report_cell::createNewReportCellWithHeaders('sue', 'Name', null),
                    Report_cell::createNewReportCellWithHeaders(5, 'GFL', 'Melbourne'),
                    Report_cell::createNewReportCellWithHeaders(7, 'BFL', 'Hawthorn')
                ),
                2 => Array (
                    Report_cell::createNewReportCellWithHeaders('mark', 'Name', null),
                    Report_cell::createNewReportCellWithHeaders(7, 'BFL', 'Hawthorn')
                ),
                3 => Array (
                    Report_cell::createNewReportCellWithHeaders('matt', 'Name', null),
                    Report_cell::createNewReportCellWithHeaders(7, 'BFL', 'Essendon'),
                    Report_cell::createNewReportCellWithHeaders(7, 'GFL', 'Carlton')
                )
            );

        $resultArray = $this->obj->getResultArray();
        //echo "<pre>" . print_r($resultArray) . "</pre>";
        $actualResultArray = $resultArray;
        $arrayOfDifferences = $this->compareExpectedActualReportCells($expectedResultArray, $actualResultArray);

        $this->assertEmpty($arrayOfDifferences);
        //$this->assertEquals($expectedResultArray, $actualResultArray);

    }

    private function compareExpectedActualReportCells($expectedArray, $actualArray) {
        $arrayOfDifferences = array();
        $arrayOfDifferencesRowNumber = 0;
        $outerArrayCount = count($actualArray);

        for($outerArrayIndex = 0; $outerArrayIndex <  $outerArrayCount; $outerArrayIndex++) {
            $innerArrayCount = count($actualArray[$outerArrayIndex]);

            for($innerArrayIndex = 0; $innerArrayIndex <  $innerArrayCount; $innerArrayIndex++) {
                $expectedReportCell = $expectedArray[$outerArrayIndex][$innerArrayIndex];
                $actualReportCell = $actualArray[$outerArrayIndex][$innerArrayIndex];
                if (!$this->isReportCellMatchingSimpleFields($expectedReportCell, $actualReportCell)) {
                    $arrayOfDifferences[$arrayOfDifferencesRowNumber]['expected'] = $expectedReportCell;
                    $arrayOfDifferences[$arrayOfDifferencesRowNumber]['actual'] = $actualReportCell;
                    $arrayOfDifferencesRowNumber++;
                }
            }
        }

        return $arrayOfDifferences;

    }

    private function isReportCellMatchingSimpleFields(Report_cell $expectedReportCell, Report_cell $actualReportCell) {
        if($expectedReportCell->getCellValue() == $actualReportCell->getCellValue() &&
            $expectedReportCell->getColumnHeaderValueFirst() == $actualReportCell->getColumnHeaderValueFirst() &&
            $expectedReportCell->getColumnHeaderValueSecond() == $actualReportCell->getColumnHeaderValueSecond()
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function test_getColumnCountForHeadingCells_TwoHeadings() {
        //Set up data
        $reportNumber = 1;
        $season = 2018;
        $region = 'Geelong';
        $ageGroup = array('Under 18');
        $umpireType = array('Senior');
        $league = array('GFL', 'BFL');
        $pdfMode = false;

        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            $reportNumber, $season, $region, $ageGroup, $umpireType, $league, $pdfMode
        );
        $dataStore = new Array_store_matches();
        $dataStoreReportParam = new Array_store_report_param();

        $this->obj->setReportType($dataStoreReportParam, $dataStore, $requestedReport);
        $this->obj->loadReportResults($dataStore);

        //Get data
        $actualArray = $this->obj->getColumnCountForHeadingCells();
        $expectedArray = array(
            array(
                array('label' => 'GFL', 'unique label' => 'GFL', 'count' => 3),
                array('label' => 'BFL', 'unique label' => 'BFL', 'count' => 2)
            ),
            array(
                array('label' => 'Geelong', 'unique label' => 'GFL|Geelong', 'count' => 1),
                array('label' => 'Melbourne', 'unique label' => 'GFL|Melbourne', 'count' => 1),
                array('label' => 'Carlton', 'unique label' => 'GFL|Carlton', 'count' => 1),
                array('label' => 'Hawthorn', 'unique label' => 'BFL|Hawthorn', 'count' => 1),
                array('label' => 'Essendon', 'unique label' => 'BFL|Essendon', 'count' => 1)

            )
        );

        $this->assertEquals($expectedArray, $actualArray);


    }

    public function test_getColumnCountForHeadingCells_OneHeading() {
        //Set up data
        $reportNumber = 6;
        $season = 2018;
        $region = 'Geelong';
        $ageGroup = array('Under 18');
        $umpireType = array('Senior');
        $league = array('GFL', 'BFL');
        $pdfMode = false;

        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            $reportNumber, $season, $region, $ageGroup, $umpireType, $league, $pdfMode
        );
        $dataStore = new Array_store_matches();
        $dataStoreReportParam = new Array_store_report_param();

        $this->obj->setReportType($dataStoreReportParam, $dataStore, $requestedReport);
        $this->obj->loadReportResults($dataStore);

        //Get data
        $actualArray = $this->obj->getColumnCountForHeadingCells();
        $expectedArray = array(
            array(
                array('label' => 'john', 'unique label' => 'john', 'count' => 1),
                array('label' => 'mark', 'unique label' => 'mark', 'count' => 1),
                array('label' => 'matt', 'unique label' => 'matt', 'count' => 1),
                array('label' => 'sue', 'unique label' => 'sue', 'count' => 1)
            )
        );

        $this->assertEquals($expectedArray, $actualArray);
    }

    public function test_getColumnCountForHeadingCells_ThreeHeadings() {
        //Set up data
        $reportNumber = 4;
        $season = 2018;
        $region = 'Geelong';
        $ageGroup = array('Seniors');
        $umpireType = array('Field');
        $league = array('GFL', 'BFL');
        $pdfMode = false;

        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            $reportNumber, $season, $region, $ageGroup, $umpireType, $league, $pdfMode
        );
        $dataStore = new Array_store_matches();
        $dataStoreReportParam = new Array_store_report_param();

        $this->obj->setReportType($dataStoreReportParam, $dataStore, $requestedReport);
        $this->obj->loadReportResults($dataStore);

        //Get data
        $actualArray = $this->obj->getColumnCountForHeadingCells();
        $expectedArray = array(
            array(
                array('label' => 'Field', 'unique label' => 'Field', 'count' => 3)
            ),
            array(
                array('label' => 'Under 18', 'unique label' => 'Field|Under 18', 'count' => 2),
                array('label' => 'Seniors', 'unique label' => 'Field|Seniors', 'count' => 1)

            ),
            array(
                array('label' => 'GFL', 'unique label' => 'Field|Under 18|GFL', 'count' => 1),
                array('label' => 'BFL', 'unique label' => 'Field|Under 18|BFL', 'count' => 1),
                array('label' => 'GFL', 'unique label' => 'Field|Seniors|GFL', 'count' => 1)

            )
        );

        $this->assertEquals($expectedArray, $actualArray);
    }




    //TODO Test this later once full app is working and I know the expected array output
    /*
    public function test_FormattedOutput() {
        $reportNumber = 1;
        $season = 2018;
        $region = 'Geelong';
        $ageGroup = array('Under 18');
        $umpireType = array('Senior');
        $league = array('GFL');
        $pdfMode = false;

        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            $reportNumber, $season, $region, $ageGroup, $umpireType, $league, $pdfMode
        );
        $dataStore = new Array_store_matches();

        $this->obj->setReportType($dataStore, $requestedReport);
        $this->obj->loadReportResults($dataStore);

        $actualArray = $this->obj->getFormattedResultsForOutput();
        echo "<pre>" . print_r($actualArray) . "</pre>";
        $expectedArray = "";
        $this->assertEquals($expectedArray, $actualArray);

    }
    */





}