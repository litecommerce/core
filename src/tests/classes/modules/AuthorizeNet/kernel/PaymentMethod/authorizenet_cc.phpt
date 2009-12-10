<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class PaymentMethod_authorizenet_ccTest extends PHPUnit_TestCase
{
	var $p;
	var $order;
	
    function PaymentMethod_authorizenet_ccTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->p =& func_new("PaymentMethod_authorizenet_cc");
		$params = new StdClass;
		$params->login = 'test';
		$params->key = 'test';
		$params->cvv2 = false;
		$params->test = 'TRUE';
		$params->currency = 'USD';
		$params->prefix = '';
		$params->url = "https://rrf.ru/~ndv/x-lite/tests/classes/modules/AuthorizeNet/authorize_net.php";
	    $this->p->params = $params;	
		$this->order =& func_new("Order");
		$this->order->set("total", 155.55);
		$this->order->_initFields();
		$this->p->cc_info = array();
		$this->p->cc_info["cc_number"] = '4111111111111111';
		$this->p->cc_info["cc_cvv2"] =  '123';
		$this->p->cc_info['cc_type'] = 'VISA';
		$this->p->cc_info['cc_date'] = '1203';
		$this->p->cc_info['name'] = 'x-lite tester';
		$this->order->setProfile(func_new("Profile", 1)); // bit-bucket
		$this->order->create();
    }

    function tearDown()
    {
		$this->order->delete();
    }

	function testFailure()
	{
		$this->p->cc_info['cc_number'] = '4222222222222222';
		$this->p->process($this->order);
		$this->assertEquals('F', $this->order->get("status"));
		$this->assertEquals('Declined', $this->order->getDetails("error"));
	}

	function testFailureURL()
	{
	    $this->p->params->url = 'wrong url';
		$this->p->process($this->order);
		$this->assertEquals('F', $this->order->get("status"));
		$this->assertEquals("Can't connect to wrong url",$this->order->getDetails("error"));
	}

	function testSuccess()
	{
		$this->p->process($this->order);
		$this->assertEquals('P', $this->order->get("status"));
		if ($this->order->get("status")!='P'){
			print($this->order->getDetails("error"));
		}
	}

	function testSuccessCVV2()
	{
	    $this->p->params->cvv2 = true;
		$this->p->process($this->order);
		$this->assertEquals('P', $this->order->get("status"));
		if ($this->order->get("status")!='P'){
			print($this->order->getDetails("error"));
		}

	}

	function testFailureCVV2()
	{
	    $this->p->params->cvv2 = true;
		$this->p->cc_info['cc_cvv2'] = '321';
		$this->p->process($this->order);
		$this->assertEquals('F', $this->order->get("status"));
		$this->assertEquals("Wrong CVV2 code", $this->order->getDetails("cvvMessage"));
	}
	function testSuccessMD5()
	{
	    $this->p->params->md5HashValue = "test";
		$this->p->process($this->order);
		$this->assertEquals('P', $this->order->get("status"));
	}

	function testFailureMD5()
	{
	    $this->p->params->md5HashValue = "te";
		$this->p->process($this->order);
		$this->assertEquals('I', $this->order->get("status"));
		$this->assertTrue(ereg("MD5 hash is invalid", $this->order->getDetails("error")));
	}

}

$suite = new PHPUnit_TestSuite("PaymentMethod_authorizenet_ccTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
