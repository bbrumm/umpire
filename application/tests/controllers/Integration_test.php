<?php

class Integration_test extends TestCase
{

    public function setUp() {
        $this->resetInstance();
        //Connect to the ci_prod database in the "testing/database" file
        $this->db = $this->CI->load->database('ci_prod', TRUE);
        //This connects to the localhost database
        //$this->db = $this->CI->load->database('default', TRUE);

    }

    public function test_Dummy() {
        $this->assertEquals(1, 1);
    }

/*
    public function test_SeasonCount() {
        $queryString = "SELECT COUNT(*) AS rowcount FROM season;";
        $query = $this->db->query($queryString);

        $resultArray = $query->result();
        $rowCount = $resultArray[0]->rowcount;
        $expectedCount = 4;

        $this->assertEquals($expectedCount, $rowCount);
    }


    public function test_SeasonsExist() {
        $queryString = "SELECT season_year FROM season ORDER BY id;";

        $query = $this->db->query($queryString);

        $resultArray = $query->result();

        $expectedArray = array(2015, 2016, 2017, 2018);

        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->season_year);
        }


    }
*/


}