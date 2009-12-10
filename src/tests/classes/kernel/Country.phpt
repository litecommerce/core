<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class CountryTest extends PHPUnit_TestCase
{
    var $country;

    function CountryTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->country = func_new("Country");
    }

    function tearDown()
    {
        unset($this->country);
    }

    function testReadAll()
    {
        $countries = $this->country->readAll();
		// test it reads something
		$this->assertTrue(count($countries) > 50);
        $countries = $this->country->readAll();
		// test it reads something
		$this->assertTrue(count($countries) > 50);
    }
}


$suite = new PHPUnit_TestSuite("CountryTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
