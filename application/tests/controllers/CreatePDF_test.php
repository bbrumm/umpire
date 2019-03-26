<?php
class CreatePDF_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }

    public function test_CreatePDF_Sample() {

        $postArray = array(
            'reportName'=>1,
            'season'=>2018,
            'age'=>'Seniors',
            'umpireType'=>'Field',
            'league'=>'BFL',
            'region'=>'Geelong',
            'PDFSubmitted'=>true
        );



        $output = $this->request('POST', ['Createpdf', 'pdf'], $postArray);



    }


}