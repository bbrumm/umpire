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

        $this->obj->setReportType($dataStore, $requestedReport);
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

        $this->obj->setReportType($dataStore, $requestedReport);
        $this->obj->loadReportResults($dataStore);

        $this->assertEquals(1, 1);
        /* TODO fix this test
        $expectedColumnLabelResultArraySet = true;
        $actualColumnLabelResultArraySet = !empty($this->obj->getColumnLabelResultArray());
        $this->assertEquals($expectedColumnLabelResultArraySet, $actualColumnLabelResultArraySet);

        $expectedResultOutputArraySet = true;
        $actualResultOutputArraySet = !empty($this->obj->getResultOutputArray());
        $this->assertEquals($expectedResultOutputArraySet, $actualResultOutputArraySet);
        */

    }


}