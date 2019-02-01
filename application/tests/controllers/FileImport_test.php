<?php
class FileImport_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }

    public function test_ImportFile() {

        $filename = "2018_Appointments_Master 2018_08_08.xls";
        $fileNameFull = "application/tests/import/". $filename;
        $postArray = array(
            'userfile'=>$fileNameFull
        );

        $_FILES['userfile'] = array(
            //'name'      =>  $fileNameFull,
            'name'      =>  $filename,
            'tmp_name'  =>  APPPATH . 'tests/import/' . $filename,
            //'tmp_name'  =>  $filename,
            'type'      =>  'xlsx',
            'size'      =>  10141,
            'error'     =>  0
        );


        //$_FILES['userfile'] = "application/tests/import/test_valid_xlsx.xlsx";

        $output = $this->request('POST', ['FileImport', 'do_upload'], $postArray);
        $expected = "Upload completed!";
        $this->assertContains($expected, $output);
    }

    public function test_ImportFile_Invalid() {
        $filename = "some_file_that_doesnt_exist.xlsx";


        $_FILES['userfile'] = array(
            //'name'      =>  $fileNameFull,
            'name'      =>  $filename,
            'tmp_name'  =>  APPPATH . 'tests/import/' . $filename,
            //'tmp_name'  =>  $filename,
            'type'      =>  'xlsx',
            'size'      =>  10141,
            'error'     =>  0
        );


        $output = $this->request('POST', ['FileImport', 'do_upload']);
        $expected = "You did not select a file to upload.";
        $this->assertContains($expected, $output);
    }

/*
 * TODO: This test takes >10 minutes to run on Travis. Look into why it takes so long.
    public function test_RunETLProcess() {
        $output = $this->request('POST', ['FileImport', 'runETLProcess']);
        $expected = "Upload completed!";
        $this->assertContains($expected, $output);

    }
*/

    public function test_ImportFile_NewData() {

        $filename = "test_new_data.xlsx";
        $fileNameFull = "application/tests/import/". $filename;
        $postArray = array(
            'userfile'=>$fileNameFull
        );

        $_FILES['userfile'] = array(
            //'name'      =>  $fileNameFull,
            'name'      =>  $filename,
            'tmp_name'  =>  APPPATH . 'tests/import/' . $filename,
            //'tmp_name'  =>  $filename,
            'type'      =>  'xlsx',
            'size'      =>  10141,
            'error'     =>  0
        );


        $output = $this->request('POST', ['FileImport', 'do_upload'], $postArray);
        $expected = "Upload completed!";
        $this->assertContains($expected, $output);
        $expected = "Missing Data";
        $this->assertContains($expected, $output);

        $expected = "<div class='centerText'>Competitions</div>";
        $this->assertContains($expected, $output);
        $expected = "<div class='centerText'>Teams</div>";
        $this->assertContains($expected, $output);


    }


}