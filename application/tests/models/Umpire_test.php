<?php
class Umpire_test extends TestCase {

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Umpire');
    }


    private $defaultID = 1;
    private $defaultFirstName = "John";
    private $defaultLastName = "Smith";
    private $defaultGamesPlayed = 0;

    public function test_ID() {
        $expected = 12;
        $newUmpire = Umpire::createUmpireAllData($expected, $this->defaultFirstName, $this->defaultLastName,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed);
        $this->assertEquals($expected, $newUmpire->getID());
    }

    public function test_FirstName() {
        $expected = "John";
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $expected, $this->defaultLastName,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed);
        $this->assertEquals($expected, $newUmpire->getFirstName());
    }

    public function test_LastName() {
        $expected = "Smith";
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $this->defaultFirstName, $expected,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed);
        $this->assertEquals($expected, $newUmpire->getLastName());
    }


    public function test_GamesPlayedPrior() {
        $expected = 5;
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $this->defaultFirstName, $this->defaultLastName,
            $expected, $this->defaultGamesPlayed,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed);
        $this->assertEquals($expected, $newUmpire->getGamesPlayedPrior());
    }

    public function test_GamesPlayedPriorText() {
        $this->expectException(InvalidArgumentException::class);
        $inputValue= "abc";
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $this->defaultFirstName, $this->defaultLastName,
            $inputValue, $this->defaultGamesPlayed,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed);
    }

    public function test_GamesPlayedOtherLeagues() {
        $expected = 6;
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $this->defaultFirstName, $this->defaultLastName,
            $this->defaultGamesPlayed, $expected,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed);
        $this->assertEquals($expected, $newUmpire->getGamesPlayedOtherLeagues());
    }

    public function test_GamesPlayedOtherLeaguesText() {
        $this->expectException(InvalidArgumentException::class);
        $inputValue= "abc";
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $this->defaultFirstName, $this->defaultLastName,
            $this->defaultGamesPlayed, $inputValue,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed);
    }

    public function test_OldGamesPlayedPrior() {
        $expected = 7;
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $this->defaultFirstName, $this->defaultLastName,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed,
            $expected, $this->defaultGamesPlayed);
        $this->assertEquals($expected, $newUmpire->getOldGamesPlayedPrior());
    }

    public function test_OldGamesPlayedPriorText() {
        $this->expectException(InvalidArgumentException::class);
        $inputValue= "abc";
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $this->defaultFirstName, $this->defaultLastName,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed,
            $inputValue, $this->defaultGamesPlayed);
    }

    public function test_OldGamesPlayedOtherLeagues() {
        $expected = 8;
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $this->defaultFirstName, $this->defaultLastName,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed,
            $this->defaultGamesPlayed, $expected);
        $this->assertEquals($expected, $newUmpire->getOldGamesPlayedOtherLeagues());
    }

    public function test_OldGamesPlayedOtherLeaguesText() {
        $this->expectException(InvalidArgumentException::class);
        $inputValue= "abc";
        $newUmpire = Umpire::createUmpireAllData($this->defaultID, $this->defaultFirstName, $this->defaultLastName,
            $this->defaultGamesPlayed, $this->defaultGamesPlayed,
            $this->defaultGamesPlayed, $inputValue);
    }

}
