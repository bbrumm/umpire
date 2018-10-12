<?php
class Report_grouping_structure_test extends TestCase {
public function setUp() {
$this->resetInstance();
$this->CI->load->model('report_param/Report_grouping_structure');
}

public function test_ObjectType() {
$expected = Report_grouping_structure::createNewReportGroupingStructure(
"one", "two", "three", "four", "five", "six", "seven");
$this->assertInstanceOf('Report_grouping_structure', $expected);

}

public function test_ReportGroupingStructureID() {
$this->obj = Report_grouping_structure::createNewReportGroupingStructure(
"one", "two", "three", "four", "five", "six", "seven");
$actual = $this->obj->getReportGroupingStructureID();
$expected = "one";
$this->assertEquals($expected, $actual);
}

public function test_GroupingType() {
$this->obj = Report_grouping_structure::createNewReportGroupingStructure(
"one", "two", "three", "four", "five", "six", "seven");
$actual = $this->obj->getGroupingType();
$expected = "two";
$this->assertEquals($expected, $actual);
}

public function test_FieldName() {
$this->obj = Report_grouping_structure::createNewReportGroupingStructure(
"one", "two", "three", "four", "five", "six", "seven");
$actual = $this->obj->getFieldName();
$expected = "three";
$this->assertEquals($expected, $actual);
}


public function test_FieldGroupOrder() {
$this->obj = Report_grouping_structure::createNewReportGroupingStructure(
"one", "two", "three", "four", "five", "six", "seven");
$actual = $this->obj->getFieldGroupOrder();
$expected = "four";
$this->assertEquals($expected, $actual);
}


public function test_MergeField() {
$this->obj = Report_grouping_structure::createNewReportGroupingStructure(
"one", "two", "three", "four", "five", "six", "seven");
$actual = $this->obj->getMergeField();
$expected = "five";
$this->assertEquals($expected, $actual);
}


public function test_GroupHeading() {
$this->obj = Report_grouping_structure::createNewReportGroupingStructure(
"one", "two", "three", "four", "five", "six", "seven");
$actual = $this->obj->getGroupHeading();
$expected = "six";
$this->assertEquals($expected, $actual);
}


public function test_GroupSizeText() {
$this->obj = Report_grouping_structure::createNewReportGroupingStructure(
"one", "two", "three", "four", "five", "six", "seven");
$actual = $this->obj->getGroupSizeText();
$expected = "seven";
$this->assertEquals($expected, $actual);
}

public function test_NullParams() {
$this->obj = Report_grouping_structure::createNewReportGroupingStructure(
null, null, null, null, null, null, null);
$actual = $this->obj->getFieldName();
$expected = null;
$this->assertEquals($expected, $actual);
}

public function test_EmptyStringParams() {
$this->obj = Report_grouping_structure::createNewReportGroupingStructure(
"", "", "", "", "", "",  "");
$actual = $this->obj->getFieldName();
$expected = "";
$this->assertEquals($expected, $actual);
}





}
