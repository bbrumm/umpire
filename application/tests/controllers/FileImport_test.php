<?php
class FileImport_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }

    public function test_ImportFile() {
        $fileNameFull = "application/tests/import/2018_Appointments_Master 2018_08_08.xls";
        $filename = "2018_Appointments_Master 2018_08_08.xls";
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


}