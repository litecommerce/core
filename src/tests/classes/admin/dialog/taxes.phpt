<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "admin/dialog/taxes.php";

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

	function test_sortRates()
	{
		$t = new  Admin_Dialog_taxes;
		$rates = array("name1:=1", "name2:=2");
		$pos = array("orderbys" => array(1=>20, 2=>10));
		$t->_sortRates($rates, $pos);
		$this->assertEquals(array("name2:=2", "name1:=1"), $rates);
	}
	
	function test_initRuleParams()
	{
		$t = new  Admin_Dialog_taxes;
		$t->taxes = new TaxRates;
		$t->taxes->_rates = $t->taxes->_predefinedSchemas["US state sales tax rates"]["tax_rates"];
		$t->initRuleParams();
		$this->assertEquals("Countries", $t->params[0]->name);
		$this->assertTrue(count($t->params[0]->values)>30);
		$this->assertEquals("States", $t->params[1]->name);
		$this->assertEquals("Cities", $t->params[2]->name);
		$this->assertTrue(is_numeric(array_search("Edmond", $t->params[2]->values)));
		$this->assertEquals("Payment method", $t->params[3]->name);
		$this->assertTrue(count($t->params[3]->values));
		$this->assertEquals(array("Food","Non-prescription Drugs","Prescription Drugs","Tax free","shipping service"), $t->params[4]->values);
	}

	function test_readTaxForm()
	{
		$t = new  Admin_Dialog_taxes;
		$t->taxes = new TaxRates;
		$_POST = array(
			"country" => "United States,United Kingfom (Great Britain)",
			"taxName" => "",
			"taxValue" => " "
			);
		$this->assertEquals(array("condition" => "country=United States,United Kingfom (Great Britain)", "action" => array(), "open"=>true), $t->_readTaxForm());

		$_POST = array(
			"country" => "United States,United Kingfom (Great Britain)",
			"taxName" => "Tax ",
			"taxValue" => " 0"
			);
		$this->assertEquals(array("condition" => "country=United States,United Kingfom (Great Britain)", "action" => "Tax:=0"), $t->_readTaxForm());

		$_POST = array(
			"taxName" => "Tax ",
			"taxValue" => " 0"
			);
		$this->assertEquals("Tax:=0", $t->_readTaxForm());

		$_POST = array(
			"taxName" => "",
			"taxValue" => ""
			);
		$this->assertEquals(null, $t->_readTaxForm());

		$_POST = array(
			"taxName" => "Tax",
			"taxValue" => "asdfkljh"
			);
		$this->assertEquals(null, $t->_readTaxForm());


	}
}


$suite = new PHPUnit_TestSuite("TaxRatesTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
