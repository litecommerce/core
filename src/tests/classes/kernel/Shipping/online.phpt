<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class Shipping_onlineTest extends PHPUnit_TestCase
{
    function Shipping_onlineTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        global $xlite;
		$c =& $xlite->get('db');
		$c->query("DELETE FROM xlite_config WHERE category='TEST'");
    }

    function tearDown()
    {
    }

	function test_normalizeZip()
	{
		$shipping = func_new("Shipping_online");
		$this->assertEquals("123123", $shipping->_normalizeZip("123123"));
		$this->assertEquals("12323", $shipping->_normalizeZip("12323"));
		$this->assertEquals("12323123", $shipping->_normalizeZip("12323 123"));
		$this->assertEquals("12323", $shipping->_normalizeZip("12323 1234"));
		$this->assertEquals("12323", $shipping->_normalizeZip("12323-1234"));
		$this->assertEquals("12323", $shipping->_normalizeZip("12323 - 1234"));
		$this->assertEquals("1a1a1a", $shipping->_normalizeZip("1a1 a1a"));
	}

	function test_getOunces()
	{
		$shipping = func_new("Shipping_online");
		$o = new myorder;
        $o->constructor();
		$o->weight = 0.55;
        global $xlite;
	 	$config =& $xlite->config;
		$config->General->weight_unit = 'lbs';
		$this->assertEquals(9, $shipping->getOunces($o));
		$config->General->weight_unit = 'oz';
		$this->assertEquals(1, $shipping->getOunces($o));
		$config->General->weight_unit = 'kg';
		$this->assertEquals(20, $shipping->getOunces($o));
		$config->General->weight_unit = 'g';
		$this->assertEquals(1, $shipping->getOunces($o));
		$o->weight = 0;
		$this->assertEquals(0, $shipping->getOunces($o));

	}

	function test_options()
	{
		$shipping = func_new("Shipping_online");
		$shipping->configCategory = "TEST";
		$shipping->optionsFields = array();
		$this->assertEquals(new StdClass, $shipping->getOptions());
		$shipping->optionsFields = array("testField", "testField1");
		$shipping->setOptions($options=(object)array("testField" => "value", "testField1" => "value1"));
		$this->assertEquals($options, $shipping->getOptions());
		$c = func_new("Config");
		$c->readConfig();
		$ooo = $shipping->getOptions();
		$this->assertEquals($options->testField, $ooo->testField);
		$this->assertEquals($options->testField1, $ooo->testField1);
		foreach ($c->findAll("category='TEST'") as $var) {
			$var->delete();
		}
	}
	
}
func_define_class("order");
class myorder extends Order__{
	var $weight;
	function getWeight()
	{
		return $this->weight;
	}
}

$suite = new PHPUnit_TestSuite("Shipping_onlineTest");
$result = PHPUnit::run($suite);

?>
