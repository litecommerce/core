<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class ShippingZoneTest extends PHPUnit_TestCase
{
    function ShippingZoneTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->c = func_new("Country");
		$this->c->set("code", "MO");
		$this->c->set("shipping_zone", "100");
		$this->c->update();
		$this->z = func_new("ShippingZone", 100);
    }

    function tearDown()
    {
		$this->c->set("shipping_zone", "0");
		$this->c->update();
    }

	function testGetCountries()
	{
		$c = $this->z->get("countries");
	    $this->assertEquals(count($c), 1);
		$this->assertEquals($c[0]->get("country"), "Macau");
	}

	function testFindAll()
	{
		$z = $this->z->findAll();
	    $this->assertTrue(count($z)>=2);
		$this->assertEquals(0, $z[0]->get("shipping_zone"));
		$this->assertEquals(100, $z[100]->get("shipping_zone"));
	}

	function testCreate()
	{
		$z =& func_new("ShippingZone");
		$z->create();
		$this->assertEquals(101, $z->get("shipping_zone"));
	}

}


$suite = new PHPUnit_TestSuite("ShippingZoneTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
