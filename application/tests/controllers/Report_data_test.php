<?php

class Report_data_test extends TestCase {

    private $dataImported = false;

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Array_library');
        $this->arrayLibrary = new Array_library();
        //Connect to the ci_prod database in the "testing/database" file
        $this->db = $this->CI->load->database('ci_prod', TRUE);
        //This connects to the localhost database, and should be used when unit testing
        $this->dbLocal = $this->CI->load->database('default', TRUE);

        //This connects to the local copy of the dbunittest database, which should be a copy of the DB created on Travis CI
        //It's helpful when debugging why something works on localhost but not on Travis
        //$this->dbLocal = $this->CI->load->database('local_dbunittest', TRUE);

        $this->importData();

    }

    private function importData() {
        if ($this->dataAlreadyImported() == false) {
            $filename = "2018_Appointments_Master 2018_08_08 Dedupe.xls";
            $fileNameFull = "application/tests/import/" . $filename;
            $postArray = array(
                'userfile' => $fileNameFull
            );

            $_FILES['userfile'] = array(
                //'name'      =>  $fileNameFull,
                'name' => $filename,
                'tmp_name' => APPPATH . 'tests/import/' . $filename,
                //'tmp_name'  =>  $filename,
                'type' => 'xlsx',
                'size' => 10141,
                'error' => 0
            );

            $output = $this->request('POST', ['FileImport', 'do_upload'], $postArray);
            $this->dataImported = true;
        }
    }

    private function dataAlreadyImported() {
        $queryString = "SELECT filename
            FROM imported_files
            WHERE imported_file_id = (
              SELECT MAX(imported_file_id)
              FROM imported_files
            );";

        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $lastImportedFilename = $resultArray[0]->filename;
        return $this->expectedFilenameMatchesActualFilename($lastImportedFilename);
    }

    private function expectedFilenameMatchesActualFilename($pActualFilename) {
        $expectedFilename = "2018_Appointments_Master_2018_08_08_Dedupe";
        if(substr($pActualFilename, 0, strlen($expectedFilename)) == $expectedFilename) {
            return true;
        } else {
            return false;
        }
    }

    public function test_DisplayReport01() {
        $postArray = array(
            'reportName'=>'1',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors', 'Reserves', 'Colts', 'Under 16'),
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDiscipline'=>array('Field', 'Boundary', 'Goal'),
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeague'=>array('GFL', 'BFL', 'GDFL', 'GJFL'),
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>01 - Umpires and Clubs (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport02() {
        $postArray = array(
            'reportName'=>'2',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors', 'Reserves', 'Colts', 'Under 16'),
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDiscipline'=>array('Field', 'Boundary', 'Goal'),
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeague'=>array('GFL', 'BFL', 'GDFL', 'GJFL'),
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>02 - Umpire Names by League (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport03() {
        $postArray = array(
            'reportName'=>'3',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 19',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL,Women'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>03 - Summary by Week (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport04() {
        $postArray = array(
            'reportName'=>'4',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>04 - Summary by Club (Matches Where No Umpires Are Recorded) (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport05() {
        $postArray = array(
            'reportName'=>'5',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>05 - Games with Zero Umpires For Each League (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport06() {
        $postArray = array(
            'reportName'=>'6',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>06 - Umpire Pairing (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport07() {
        $postArray = array(
            'reportName'=>'7',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroup'=>array('Seniors', 'Reserves', 'Colts', 'Under 16'),
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDiscipline'=>array('Field', 'Boundary', 'Goal'),
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeague'=>array('GFL', 'BFL', 'GDFL', 'GJFL'),
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>07 - Games with 2 or 3 Field Umpires (2018)</h1>";
        $this->assertContains($expected, $output);
    }

    public function test_DisplayReport08() {
        $postArray = array(
            'reportName'=>'8',
            'season'=>2018,
            'rdRegion'=>'Geelong',
            'chkRegionHidden'=>'Geelong',
            'chkAgeGroupHidden'=>'Seniors,Reserves,Colts,Under 16',
            'chkUmpireDisciplineHidden'=>'Field,Boundary,Goal',
            'chkLeagueHidden'=>'GFL,BFL,GDFL,GJFL'
        );

        $output = $this->request('POST', ['Report', 'index'], $postArray);
        $expected = "<h1>08 - Umpire Games Tally</h1>";
        $this->assertContains($expected, $output);
    }


}