<?php
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;


class Webdriver_test extends TestCase
{

    public function setUp() {
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $this->driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
    }

    public function tearDown() {
        $this->driver->close();
    }

    private function login() {

        $this->driver->get("http://localhost:8888/umpire/");
        $this->driver->findElement(WebDriverBy::id("username"))->sendKeys("bbrumm");
        $this->driver->findElement(WebDriverBy::id("password"))->sendKeys("bbrumm2017");

        $this->driver->findElement(WebDriverBy::id('loginBtn'))->click();

    }

    private function getElement($pElementID) {
        return $this->driver->findElement(WebDriverBy::id($pElementID));
    }

    private function isElementTypeCorrect($pElement, $pExpectedType) {
        return ($pElement->getAttribute("type") == $pExpectedType);
    }

    private function isElementSelected($pElement) {
        return ($pElement->isSelected());
    }

    private function isElementEnabled($pElement) {
        return ($pElement->isEnabled());
    }

    private function loadArrayOfSelectionElements($expectedElementArray) {
        $actualElementArray = array ();
        $arrayCount = count($expectedElementArray);
        for($i=0; $i<$arrayCount; $i++) {
            $pCurrentElement = $this->getElement($expectedElementArray[$i]["id"]);
            $actualElementArray[] = array(
                "id"=>$expectedElementArray[$i]["id"],
                "type"=>$pCurrentElement->getAttribute("type"),
                "enabled"=>$pCurrentElement->isEnabled(),
                "selected"=>$pCurrentElement->isSelected()
            );
        }

        return $actualElementArray;
    }

    public function test_AllCheckboxesAppear() {
        $this->login();

        $expectedElementArray = array(
            array("id"=>"Geelong", "type"=>"radio", "enabled"=>true, "selected"=>true),
            array("id"=>"Colac", "type"=>"radio", "enabled"=>true, "selected"=>false),
            array("id"=>"LeagueSelectAll", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"BFL", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"GFL", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"GDFL", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"GJFL", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"CDFNL", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Women", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"UmpireDisciplineSelectAll", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"Field", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"Boundary", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"Goal", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"AgeGroupSelectAll", "type"=>"checkbox", "enabled"=>true, "selected"=>true),
            array("id"=>"Seniors", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Reserves", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Colts", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 19", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 17.5", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 17", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 16", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 15", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 14.5", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 14", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 13", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 12", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 19 Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 18 Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 15 Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 12 Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Youth Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Junior Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false)

        );

        $actualElementArray = $this->loadArrayOfSelectionElements($expectedElementArray);

        $this->assertEquals($expectedElementArray, $actualElementArray);

    }

    public function test_Report1ChangeToColac() {
        $this->login();

        $expectedElementArray = array(
            array("id"=>"Geelong", "type"=>"radio", "enabled"=>true, "selected"=>false),
            array("id"=>"Colac", "type"=>"radio", "enabled"=>true, "selected"=>true),
            array("id"=>"LeagueSelectAll", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"BFL", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"GFL", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"GDFL", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"GJFL", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"CDFNL", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"Women", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"UmpireDisciplineSelectAll", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"Field", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"Boundary", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"Goal", "type"=>"checkbox", "enabled"=>true, "selected"=>false),
            array("id"=>"AgeGroupSelectAll", "type"=>"checkbox", "enabled"=>true, "selected"=>true),
            array("id"=>"Seniors", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Reserves", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Colts", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 19", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 17.5", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 17", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 16", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 15", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 14.5", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 14", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 13", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 12", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 19 Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 18 Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 15 Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Under 12 Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Youth Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false),
            array("id"=>"Junior Girls", "type"=>"checkbox", "enabled"=>false, "selected"=>false)

        );

        $this->driver->findElement(WebDriverBy::id('Colac'))->click();

        $actualElementArray = $this->loadArrayOfSelectionElements($expectedElementArray);

        $this->assertEquals($expectedElementArray, $actualElementArray);

    }



}