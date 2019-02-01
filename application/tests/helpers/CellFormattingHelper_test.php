<?php
class CellFormattingHelper_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->CI->load->helper('cell_formatting_helper');

    }

    public function testLevel1Table() {
        $levelToTest = 1;
        $isTable = true;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "cellNumber cellNormal";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel2Table() {
        $levelToTest = 2;
        $isTable = true;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "cellNumber cellNormal";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel3Table() {
        $levelToTest = 3;
        $isTable = true;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "cellNumber cellLevel1";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel4Table() {
        $levelToTest = 4;
        $isTable = true;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "cellNumber cellLevel2";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel5Table() {
        $levelToTest = 5;
        $isTable = true;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "cellNumber cellLevel3";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel6Table() {
        $levelToTest = 6;
        $isTable = true;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "cellNumber cellLevel4";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevelNullTable() {
        $levelToTest = null;
        $isTable = true;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "cellNumber cellNormal";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevelInvalidTable() {
        $levelToTest = "word";
        $isTable = true;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "cellNumber cellNormal";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel1NoTable() {
        $levelToTest = 1;
        $isTable = false;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "divCell divCellNumber";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel2NoTable() {
        $levelToTest = 2;
        $isTable = false;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "divCell divCellNumber";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel3NoTable() {
        $levelToTest = 3;
        $isTable = false;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "divCell divCellNumber cellLevel1";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel4NoTable() {
        $levelToTest = 4;
        $isTable = false;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "divCell divCellNumber cellLevel2";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel5NoTable() {
        $levelToTest = 5;
        $isTable = false;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "divCell divCellNumber cellLevel3";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel6NoTable() {
        $levelToTest = 6;
        $isTable = false;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "divCell divCellNumber cellLevel4";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevelNullNoTable() {
        $levelToTest = null;
        $isTable = false;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "divCell divCellNumber";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevelInvalidNoTable() {
        $levelToTest = "word";
        $isTable = false;
        $output = getCellClassNameFromOutputValue($levelToTest, $isTable);
        $expectedOutput = "divCell divCellNumber";

        $this->assertEquals($expectedOutput, $output);
    }
}