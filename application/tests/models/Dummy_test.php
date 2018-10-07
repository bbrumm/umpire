<?php

class DummyTest extends TestCase {
	public function setUp() {
        $this->resetInstance();


    }
    public function test_firstDummy() {
    	$this->assertEquals(1, 1); 
    }



}
?>