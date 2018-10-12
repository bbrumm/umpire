<?php
class Umpire_test extends TestCase {
public function setUp() {
$this->resetInstance();
$this->CI->load->model('Umpire');
$this->obj = $this->CI->Umpire;
}


private $id;
private $firstName;
private $lastName;
private $gamesPlayedPrior;
private $gamesPlayedOtherLeagues;
private $oldGamesPlayedPrior;
private $oldGamesPlayedOtherLeagues;

public function test_ID() {
$expected = 12;
$this->obj->setID($expected );
$this->assertEquals($expected, $this->obj->getID());
}

public function test_FirstName() {
$expected = "John";
$this->obj->setFirstName($expected );
$this->assertEquals($expected, $this->obj->getFirstName());
}

public function test_LastName() {
$expected = "Smith";
$this->obj->setLastName($expected );
$this->assertEquals($expected, $this->obj->getLastName());
}


public function test_GamesPlayedPrior() {
$expected = 5;
$this->obj->setGamesPlayedPrior($expected );
$this->assertEquals($expected, $this->obj->getGamesPlayedPrior());
}

public function test_GamesPlayedPriorText() {
$this->expectException(InvalidArgumentException::class);
$inputValue= "abc";
$this->obj->setGamesPlayedPrior($inputValue);
}

public function test_GamesPlayedOtherLeagues() {
$expected = 6;
$this->obj->setGamesPlayedOtherLeagues($expected );
$this->assertEquals($expected, $this->obj->getGamesPlayedOtherLeagues());
}

public function test_GamesPlayedOtherLeaguesText() {
$this->expectException(InvalidArgumentException::class);
$inputValue= "abc";
$this->obj->setGamesPlayedOtherLeagues($inputValue);
}

public function test_OldGamesPlayedPrior() {
$expected = 7;
$this->obj->setOldGamesPlayedPrior($expected );
$this->assertEquals($expected, $this->obj->getOldGamesPlayedPrior());
}

public function test_OldGamesPlayedPriorText() {
$this->expectException(InvalidArgumentException::class);
$inputValue= "abc";
$this->obj->setOldGamesPlayedPrior($inputValue);
}

public function test_OldGamesPlayedOtherLeagues() {
$expected = 8;
$this->obj->setOldGamesPlayedOtherLeagues($expected );
$this->assertEquals($expected, $this->obj->getOldGamesPlayedOtherLeagues());
}

public function test_OldGamesPlayedOtherLeaguesText() {
$this->expectException(InvalidArgumentException::class);
$inputValue= "abc";
$this->obj->setOldGamesPlayedOtherLeagues($inputValue);
}

}
