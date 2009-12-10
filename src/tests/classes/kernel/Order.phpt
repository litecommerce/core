<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

$o = func_new("Order");

class OrderTest extends PHPUnit_TestCase
{
	var $order;

    function OrderTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->order =& func_new("myOrder");
		$this->order->create();
		$oi = func_new("OrderItem");
        $product = func_new("Product", 141);
		$oi->set("product", $product);
		$this->order->addItem($oi);
		$oi = func_new("OrderItem");
		$oi->set("product", func_new("Product", 141));
		$this->order->addItem($oi); // two
		$this->order->setProfileCopy(func_new("Profile", 1));// bit-bucket
		$this->order->calcTotals();
		$this->order->update();
    }

    function tearDown()
    {
		$this->order->delete();
    }
   
    function testcalcShippingRates()
    {
        $this->order->setProfileCopy(func_new("Profile", 1));
        $r = $this->order->calcShippingRates();
        $this->assertEquals(1, count($r));
    }
    
    function testisShippingAvailable()
    {
        $this->assertTrue($this->order->is("shipped"));
        $this->assertTrue($this->order->isShippingAvailable());
    }

	function test_starting_number()
	{
		global $xlite;
        $config =& $xlite->config;
		$xlite->call("db.query", "DELETE FROM xlite_orders");
		$config->set("General.order_starting_number", 10000);
		$order = func_new("myOrder");
		$order->create();
		$this->assertEquals(10000, $order->get("order_id"));
		$order->delete();
	}

	function testAddItem()
	{
		$this->assertEquals(1, count($this->order->get("items")));
		$items = $this->order->get("items");
		// first item
		$this->assertEquals(2, $items[0]->get("amount"));
	}	

	function testStatusChange()
	{
		$this->order->set("status", "F");
		$this->order->update();
		$this->assertTrue($this->order->failedCalled, "failed() was not called");
		$this->order->set("status", "P");
		$this->order->update();
		$this->assertTrue($this->order->processedCalled, "processed() was not called");
		$this->order->set("status", "D");
		$this->order->update();
		$this->assertTrue($this->order->declinedCalled, "declined() was not called");
	}

	function test_taxes()
	{
		$order = func_new("Order");
		$profile = func_new("Profile", 1);
		$order->create();
		$oi = func_new("OrderItem");
		$oi->set("product", func_new("Product", 69));
		$order->addItem($oi);
		$order->set("profileCopy", $profile);
		$order->set("allTaxes", array("Tax" => 1.5));
		$order->update();
		$order = func_new("Order", $order->get("order_id"));
		$this->assertEquals(array("Tax" => 1.5), $order->get("allTaxes"));
		$order->delete();
	}

	function test_sortRates()
	{
		$s1 = func_new("Shipping");
		$s1->set("name", "Shipping1");
		$s1->set("order_by", 10);
		$r1 = func_new("Object");
		$r1->shipping =& $s1;
		$s2 = func_new("Shipping");
		$s2->set("name", "Shipping2");
		$s2->set("order_by", 20);
		$r2 = func_new("Object");
		$r2->shipping =& $s2;
		$s3 = func_new("Shipping");
		$s3->set("name", "Shipping3");
		$s3->set("order_by", 30);
		$r3 = func_new("Object");
		$r3->shipping =& $s3;
		$rates = array(
			"Shipping3" => $r3,
			"Shipping2" => $r2,
			"Shipping1" => $r1
		);
		$o = func_new("Order");
		$o->_sortRates($rates);
		$this->assertRates($rates);
		$o->_sortRates($rates);
		$this->assertRates($rates);
	}

	function assertRates(&$rates)
	{
		$rates1 = array();
		foreach ($rates as $k => $v) {
			$rates1[$k] = $v->shipping->get("name");
		}
		$this->assertEquals(array(
			"Shipping1" => "Shipping1",
			"Shipping2" => "Shipping2",
			"Shipping3" => "Shipping3"), $rates1);

	}

}

class myOrder extends Order__
{
	var $processedCalled = false;
	var $declinedCalled = false;
	var $failedCalled = false;
	function processed() { $this->processedCalled = true; }
	function declined() { $this->declinedCalled = true; }
	function failed() { $this->failedCalled = true; }
	function getShippingCost() { return 1; }
}

$suite = new PHPUnit_TestSuite("OrderTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
