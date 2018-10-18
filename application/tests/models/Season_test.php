<?php
class Season_test extends TestCase {
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Season');
    }
    
    public function test_SeasonID() {
        $inputID = 1;
        $this->obj = new Season();
        $this->obj->setSeasonID($inputID);
        $expected = 1;
        $actual = $this->obj->getSeasonID();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_SeasonIDText() {
        $inputID = "abc";
        $this->obj = new Season();
        $this->obj->setSeasonID($inputID);
        $expected = "abc";
        $actual = $this->obj->getSeasonID();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_SeasonYearEarly() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = 1900;
        $this->obj = new Season();
        $this->obj->setSeasonYear($inputID);
    }
    
    public function test_SeasonYearMin() {
        $inputID = 2000;
        $this->obj = new Season();
        $this->obj->setSeasonYear($inputID);
        $expected = 2000;
        $actual = $this->obj->getSeasonYear();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_SeasonYearRecent() {
        $inputID = 2015;
        $this->obj = new Season();
        $this->obj->setSeasonYear($inputID);
        $expected = 2015;
        $actual = $this->obj->getSeasonYear();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_SeasonYearFuture() {
        $inputID = 2099;
        $this->obj = new Season();
        $this->obj->setSeasonYear($inputID);
        $expected = 2099;
        $actual = $this->obj->getSeasonYear();
        $this->assertEquals($expected, $actual);
    }
    
    public function test_SeasonYearText() {
        $this->expectException(InvalidArgumentException::class);
        $inputID = "www";
        $this->obj = new Season();
        $this->obj->setSeasonYear($inputID);
    }

    public function test_CreateSeasonFromID() {
        $id = 2;
        $season = Season::createSeasonFromID($id);

        $this->assertInstanceOf('Season', $season);
        $this->assertEquals($id, $season->getSeasonID());
    }
}