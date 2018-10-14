<?php
class Requested_report_model_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Requested_report_model');
    }
    
    public function test_ReportNumber() {
        $inputReportNumber = 1;
        $inputSeason = 2016;
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = false;
        
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
        $expected = 1;
        $this->assertEquals($expected, $this->obj->getReportNumber());
    }
    
    public function test_Season() {
        $inputReportNumber = 1;
        $inputSeason = 2016;
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = false;
        
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
        $expected = 2016;
        $this->assertEquals($expected, $this->obj->getSeason());
    }
    
    public function test_Region() {
        $inputReportNumber = 1;
        $inputSeason = 2016;
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = false;
        
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
        $expected = 'ABC';
        $this->assertEquals($expected, $this->obj->getRegion());
    }
    
    public function test_AgeGroup() {
        $inputReportNumber = 1;
        $inputSeason = 2016;
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = false;
        
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
        $expected = 'Q';
        $this->assertEquals($expected, $this->obj->getAgeGroup());
    }
    
    public function test_UmpireType() {
        $inputReportNumber = 1;
        $inputSeason = 2016;
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = false;
        
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
        $expected = 'R';
        $this->assertEquals($expected, $this->obj->getUmpireType());
    }
    
    public function test_League() {
        $inputReportNumber = 1;
        $inputSeason = 2016;
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = false;
        
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
        $expected = 'S';
        $this->assertEquals($expected, $this->obj->getLeague());
    }
    
    public function test_PDFMode() {
        $inputReportNumber = 1;
        $inputSeason = 2016;
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = true;
        
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
        $expected = true;
        $this->assertEquals($expected, $this->obj->getPDFMode());
    }
    
    public function test_ReportNumberText() {
        $this->expectException(InvalidArgumentException::class);
        $inputReportNumber = "abcde";
        $inputSeason = 2016;
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = true;
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
    }
    
    public function test_SeasonText() {
        $this->expectException(InvalidArgumentException::class);
        $inputReportNumber = 1;
        $inputSeason = "bhfwe";
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = true;
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
    }
    
    public function test_PDFModeText() {
        $this->expectException(InvalidArgumentException::class);
        $inputReportNumber = 1;
        $inputSeason = 2016;
        $inputRegion = 'ABC';
        $inputAgeGroup = 'Q';
        $inputUmpireType = 'R';
        $inputLeague = 'S';
        $inputPDFMode = "abcd";
        $this->obj = Requested_report_model::createRequestedReportFromValues(
            $inputReportNumber, $inputSeason, $inputRegion, $inputAgeGroup, $inputUmpireType, $inputLeague, $inputPDFMode);
    }
    
    
}