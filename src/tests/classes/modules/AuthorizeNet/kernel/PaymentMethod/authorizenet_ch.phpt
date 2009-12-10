<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class PaymentMethod_authorizenet_chTest extends PHPUnit_TestCase
{
	var $p;
	var $order;
	
    function PaymentMethod_authorizenet_chTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->p =& func_new("PaymentMethod_authorizenet_ch");
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
		$this->p->ch_info = array();
		$this->p->ch_info["ch_routing_number"] = 'x_Bank_ABA_Code';
		$this->p->ch_info["ch_acct_number"] =  'x_Bank_Acct_Num';
		$this->p->ch_info['ch_type'] = 'CHECKING';
		$this->p->ch_info['ch_bank_name'] = 'x_Bank_Name';
		$this->p->ch_info['ch_acct_name'] = 'x_Bank_Acct_Name';
		$this->order->setProfile(func_new("Profile")); // bit-bucket
		$this->order->create();
    }

    function tearDown()
    {
		$this->order->delete();
    }

	function testSuccess()
	{
		$this->p->process($this->order);
		$this->assertEquals('P', $this->order->get("status"));
		if ($this->order->get("status")!='P'){
			print($this->order->getDetails("error"));
		}
	}

}

$suite = new PHPUnit_TestSuite("PaymentMethod_authorizenet_chTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
