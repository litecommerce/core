<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class PaymentMethodTest extends PHPUnit_TestCase
{
    var $paymentMethod;
    
    function PaymentMethodTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->paymentMethod =& func_new("PaymentMethod");
    }

    function testFind()
    {
        $method =& func_new('PaymentMethod', 'credit_card');
        $this->assertTrue(is_a($method, "paymentmethod__"));
        $this->assertEquals("Credit Card", $method->get("name"));
    }
    function testParams()
    {
        $method =& func_new('PaymentMethod', 'credit_card');
        $method->set("params.TEST", "TSET");
        $this->assertEquals("TSET", $method->get("params.TEST"));
        $params =  $method->get("params");
        $params["asd"] = "dsa";
        $method->set("params", $params);
        $method->update();
        $method =& func_new('PaymentMethod', 'credit_card');
        $this->assertEquals("TSET", $method->get("params.TEST"));
        $this->assertEquals("dsa", $method->get("params.asd"));
    }


	function test_registerMethod()
	{
		global $_registered_methods;
		$_registered_methods = array("phone_ordering");
		$pm =& func_new("PaymentMethod");
		$methods = $pm->findAll();
		$this->assertEquals(1, count($methods));
		$this->assertEquals('phone_ordering', $methods[0]->get("payment_method"));
		$pm->registerMethod('purchase_order');
		$pm =& func_new("PaymentMethod");
		$methods = $pm->findAll();
		$this->assertEquals(2, count($methods));
		$this->assertEquals('phone_ordering', $methods[1]->get("payment_method")
		);
		$this->assertEquals('purchase_order', $methods[0]->get("payment_method")
		);
	}
}


$suite = new PHPUnit_TestSuite("PaymentMethodTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
