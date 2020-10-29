<?php

class Season_selector_test extends TestCase
{

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Season_selector');
        $this->obj = $this->CI->Season_selector;
    }

    public function test_SeasonRecordsExist() {
        $currentYear = date('Y');
        $seasonList = $this->obj->getListOfSeasons($currentYear);

        $expectedCount = 0;
        $actualCount = count($seasonList);
        $this->assertGreaterThan($expectedCount, $actualCount);
    }

    public function test_SeasonsAreCurrentOrPast() {
        $currentYear = date('Y');
        $seasonList = $this->obj->getListOfSeasons($currentYear);

        $seasonCount = count($seasonList);
        for ($i=0; $i < $seasonCount; $i++) {
            $this->assertLessThanOrEqual($currentYear, $seasonList[$i]->getSeasonYear());
        }
    }

    public function test_SeasonsValidAsOf2021() {
        $currentYear = 2021;
        $seasonList = $this->obj->getListOfSeasons($currentYear);

        $seasonCount = count($seasonList);
        for ($i=0; $i < $seasonCount; $i++) {
            $this->assertLessThanOrEqual($currentYear, $seasonList[$i]->getSeasonYear());
        }
    }

    public function test_SeasonsValidAsOf2022() {
        $currentYear = 2022;
        $seasonList = $this->obj->getListOfSeasons($currentYear);

        $seasonCount = count($seasonList);
        for ($i=0; $i < $seasonCount; $i++) {
            $this->assertLessThanOrEqual($currentYear, $seasonList[$i]->getSeasonYear());
        }
    }

    public function test_SeasonsValidAsOf2023() {
        $currentYear = 2023;
        $seasonList = $this->obj->getListOfSeasons($currentYear);

        $seasonCount = count($seasonList);
        for ($i=0; $i < $seasonCount; $i++) {
            $this->assertLessThanOrEqual($currentYear, $seasonList[$i]->getSeasonYear());
        }
    }

    public function test_SeasonsValidAsOf2024() {
        $currentYear = 2024;
        $seasonList = $this->obj->getListOfSeasons($currentYear);

        $seasonCount = count($seasonList);
        for ($i=0; $i < $seasonCount; $i++) {
            $this->assertLessThanOrEqual($currentYear, $seasonList[$i]->getSeasonYear());
        }
    }

}