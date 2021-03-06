<?php
class CellFormattingHelper_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->CI->load->model('Cell_formatting_helper');
        $this->obj = new Cell_formatting_helper();

    }

    public function testLevel1Table() {
        $levelToTest = 1;
        $output = $this->obj->getCellClassNameForTableFromOutputValue($levelToTest);
        $expectedOutput = "cellNumber cellNormal";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel2Table() {
        $levelToTest = 2;
        $output = $this->obj->getCellClassNameForTableFromOutputValue($levelToTest);
        $expectedOutput = "cellNumber cellNormal";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel3Table() {
        $levelToTest = 3;
        $output = $this->obj->getCellClassNameForTableFromOutputValue($levelToTest);
        $expectedOutput = "cellNumber cellLevel1";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel4Table() {
        $levelToTest = 4;
        $output = $this->obj->getCellClassNameForTableFromOutputValue($levelToTest);
        $expectedOutput = "cellNumber cellLevel2";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel5Table() {
        $levelToTest = 5;
        $output = $this->obj->getCellClassNameForTableFromOutputValue($levelToTest);
        $expectedOutput = "cellNumber cellLevel3";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel6Table() {
        $levelToTest = 6;
        $output = $this->obj->getCellClassNameForTableFromOutputValue($levelToTest);
        $expectedOutput = "cellNumber cellLevel4";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevelNullTable() {
        $levelToTest = null;
        $output = $this->obj->getCellClassNameForTableFromOutputValue($levelToTest);
        $expectedOutput = "cellNumber cellNormal";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevelInvalidTable() {
        $levelToTest = "word";
        $output = $this->obj->getCellClassNameForTableFromOutputValue($levelToTest);
        $expectedOutput = "cellNumber cellNormal";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel1NoTable() {
        $levelToTest = 1;
        $output = $this->obj->getCellClassNameForPDFFromOutputValue($levelToTest);
        $expectedOutput = "divCell divCellNumber";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel2NoTable() {
        $levelToTest = 2;
        $output = $this->obj->getCellClassNameForPDFFromOutputValue($levelToTest);
        $expectedOutput = "divCell divCellNumber";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel3NoTable() {
        $levelToTest = 3;
        $output = $this->obj->getCellClassNameForPDFFromOutputValue($levelToTest);
        $expectedOutput = "divCell divCellNumber cellLevel1";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel4NoTable() {
        $levelToTest = 4;
        $output = $this->obj->getCellClassNameForPDFFromOutputValue($levelToTest);
        $expectedOutput = "divCell divCellNumber cellLevel2";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel5NoTable() {
        $levelToTest = 5;
        $output = $this->obj->getCellClassNameForPDFFromOutputValue($levelToTest);
        $expectedOutput = "divCell divCellNumber cellLevel3";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevel6NoTable() {
        $levelToTest = 6;
        $output = $this->obj->getCellClassNameForPDFFromOutputValue($levelToTest);
        $expectedOutput = "divCell divCellNumber cellLevel4";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevelNullNoTable() {
        $levelToTest = null;
        $output = $this->obj->getCellClassNameForPDFFromOutputValue($levelToTest);
        $expectedOutput = "divCell divCellNumber";

        $this->assertEquals($expectedOutput, $output);
    }

    public function testLevelInvalidNoTable() {
        $levelToTest = "word";
        $output = $this->obj->getCellClassNameForPDFFromOutputValue($levelToTest);
        $expectedOutput = "divCell divCellNumber";

        $this->assertEquals($expectedOutput, $output);
    }
}