<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class TaxRatesTest extends PHPUnit_TestCase
{
    function TaxRatesTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
    }

    function tearDown()
    {
    }

	function test_setOrder()
	{
        global $xlite;
		$xlite->config->set("Taxes.use_billing_info","Y");
		$profile = func_new("Profile");
		$profile->set("billing_state", 6); // Colorado
		$profile->set("billing_country", "US");
		$profile->set("membership", "Wholesale");
		$order = func_new("Order");
		$order->set("profile", $profile);
		
		// init tax rules
		$tax = func_new("TaxRates");
		$tax->set("order", $order);
		$this->assertEquals(array("state" => "Colorado", "country" => "United States",
			"city" => "", "membership" => "Wholesale"), $tax->_conditionValues);
	}

	function test_setOrderItem()
	{
		$oi = func_new("OrderItem");
		$product = func_new("Product", 69); // BOOK0089
		$product->set("tax_class", "Food");
		$oi->set("product", $product);

		$tax = func_new("TaxRates");
		$tax->set("orderItem", $oi);
		$this->assertEquals(array("product class" => "Food", "category" => "23", "cost" => 59.99), $tax->_conditionValues);
	}

	function test_calcFormula()
	{
		$tax = func_new("TaxRates");
		$tax->_taxValues = array("Tax" => "= state tax + (1+state tax/100.0)*city tax", "state tax" => "1", "city tax" => 2);
		$this->assertEquals(3.02, $tax->_calcFormula("=Tax"));
	}

	function test_interpretCondition()
	{
		$tax = func_new("TaxRates");
		$tax->_conditionValues = array("state" => "CO");
		$this->assertTrue($tax->_interpretCondition("state = CO "));
		$this->assertTrue($tax->_interpretCondition("state=MN, CO, ND "));
		$this->assertTrue($tax->_interpretCondition(""));
		$this->assertFalse($tax->_interpretCondition("state=MN,ND"));
		$this->assertFalse($tax->_interpretCondition("state=CO AND country=RU"));
	}

    function test_compareZip()
    {
        $tax = func_new("TaxRates");
        $this->assertTrue($tax->_compareZip('432002', array('432002-432004', '432')));
        $this->assertFalse($tax->_compareZip('432002', array()));
        $this->assertTrue($tax->_compareZip('432002', array('432000-433000')));
    }

	function test_setSchema()
	{
		$tax = func_new("TaxRates");
		$tax->setPredefinedSchema("US state sales tax rates");
		$order = func_new("Order");
		$order->create();
		$item = func_new("OrderItem");
		$item->set("product", func_new("Product",69)); // $59.99
		$order->addItem($item);
		$order->set("profile", func_new("Profile",1)); // bit-bucket
		global $xlite;
		$xlite->config->Taxes->use_billing_info = false;
		$xlite->config->Taxes->prices_include_tax = false;
		$order->calcTax();
		$this->assertEquals(round(59.99*0.045,2), $order->get("tax"));
//		$order->delete();
		$tax->setPredefinedSchema("One global tax value");
		$order->calcTax();
		$this->assertEquals(0, $order->get("tax"));
	}

	function test_getProductClasses()
	{
		$tax = func_new("TaxRates");
		$tax->_rates = array(
			"product class:=hi",
			array("condition" => "state=AS,SD AND product class=hi",
				"action" => "action"),
			array("condition" => "state=AS,SD AND product class=hi1",
				"action" => array(
					array("condition" => "product class=hi2", "action" => "action"),
					array("condition" => "product class=hi2", "action" => "action"))
			)
		);	

		$this->assertEquals(array("hi", "hi1", "hi2"), $tax->getProductClasses());
	}

	function test_getTaxNames()
	{
		$tax = func_new("TaxRates");
		$tax->_rates = array(
			"Tax:=hi",
			array("condition" => "state=AS,SD AND product class=hi",
				"action" => "Tax2:==Tax"),
			array("condition" => "state=AS,SD AND product class=hi1",
				"action" => array(
					array("condition" => "product class=hi2", "action" => "Tax2:=1"),
					array("condition" => "product class=hi2", "action" => "Tax3:=2"))
			)
		);	

		$this->assertEquals(array("Tax", "Tax2", "Tax3"), array_values($tax->get("taxNames")));
	}

}


$suite = new PHPUnit_TestSuite("TaxRatesTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
