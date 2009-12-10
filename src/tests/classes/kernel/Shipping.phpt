<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class ShippingTest extends PHPUnit_TestCase
{
	var $shipping;
	var $rate;

    function ShippingTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$s = func_new("Shipping");
		foreach ($s->findAll("name='Test Shipping'") as $sh) {
			$sh->delete();
		}
		$this->shipping =& func_new("Shipping");
		$this->shipping->set("name", "Test Shipping");
		$this->shipping->set("class", "offline");
		$this->shipping->set("enabled", 1);
		$this->shipping->set("destination", "L");
		$this->shipping->create();
		$this->shipping->get("shipping_id");
		$this->rate =& func_new("ShippingRate");
		$this->rate->set("shipping_id", $this->shipping->get("shipping_id"));
		$this->rate->set("min_weight", 100);
		$this->rate->set("flat", 10);
		$this->rate->create();
    }

    function tearDown()
    {
		$this->shipping->delete();
		$this->rate->delete();
        global $xlite;
		$conn =& $xlite->get('db');
		$conn->query("delete from xlite_shipping where name like 'Test Shipping%'");
    }

	function testGetShippingMethods()
	{
		$s =& func_new("Shipping");
		$s->set("class", "offline");
		$methods =& $s->get("shippingMethods");
		$this->assertTrue(count($methods)==3, "Wrong count of shipping methods found for class=offline: ".count($methods));
		$this->assertEquals($methods[0]->get("name"), "Test Shipping");
	}

	function testGetRates()
	{
		$order =& func_new("Cart");
		$order->create();
		$p = func_new("Product", 106);
		$p->set("weight", 100);
		$oi =& func_new("OrderItem");
		$oi->set("product", $p);
		$order->addItem($oi);
		$shipping_id = $this->shipping->get("shipping_id");
		$order->set("shipping_id", $shipping_id);
		$order->set("profile", func_new("Profile"));
		$order->set("profile.shipping_country", "US");
		$order->config->set("Company.location_country", "US");
		$this->assertTrue($order->is("shippingDefined"));
		$rates = $order->get("shippingRates");
	    $this->assertTrue(count($rates)>=2);
		$this->assertEquals(10, $rates[$shipping_id]->rate);
	}

	function test_normalizeName()
	{
		$name = $this->shipping->_normalizeName(" Some  \nnon-\nnormalized \tText ");
		$this->assertEquals("Some non- normalized Text", $name);
	}
	
	function testgetService()
	{
		$shipping = $this->shipping->getService("offline", "Test Shipping ", "L");
		$this->assertEquals($shipping->get("shipping_id"), $this->shipping->get("shipping_id"));
		$shipping = $this->shipping->getService("offline", "Test Shipping2", "L");
		$this->assertEquals($shipping->get("name"), "Test Shipping2");
		if ($shipping->get("name") == "Test Shipping2") {
			// newly created
			$shipping->delete();
		}
	}

}

$suite = new PHPUnit_TestSuite("ShippingTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
