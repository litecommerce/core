<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class TwoCheckoutCom_processorTest extends PHPUnit_TestCase
{
	var $p;
	var $order;
	
    function TwoCheckoutCom_processorTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->p =& func_new("PaymentMethod_2checkout");
		$params = func_new("Object");
		$params->login = '123';
		$params->url = "https://rrf.ru/~ndv/x-lite/tests/classes/modules/2CheckoutCom/2checkout.php";
		$params->md5HashValue = "test";
		$this->oldparams = $this->setParams($params);
		$this->order =& func_new("Order");
		$this->order->set("total", 155.55);
		$this->order->set("payment_method", "2checkout");
		$this->order->setProfile(func_new("Profile", 1)); // bit-bucket
		$this->order->create();
    }

    function tearDown()
    {
		$this->order->delete();
		$this->setParams($this->oldparams);
    }

	function request($x_response_code = 1)
	{
		$https = func_new("HTTPS");
		$https->url = $this->p->get("params.url");
        global $xlite;
		$https->data = array(
			'x_Login' => $this->p->get("params.login"),
			'x_invoice_num' => $this->order->get("order_id"),
			"x_amount" => $this->order->get("total"),
			"x_First_Name" => $this->order->get("profile.billing_firstname"),
			"x_Last_Name" => $this->order->get("profile.billing_lastname"),
			"return_url" => $xlite->shopURL("cart.php?target=checkout&action=callback&order_id_name=x_invoice_num"),
			"x_response_code" => $x_response_code
		);	
		$https->request();
		$this->order =& func_new("Order", $this->order->get("order_id"));
		return $https->response;
	}
	
	function testFailure()
	{
		print $this->request(2);
		$this->assertEquals('F', $this->order->get("status"));
	}

	function testSuccess()
	{
		$this->request();
		$this->assertEquals('P', $this->order->get("status"));
	}

/*	function testFailureMD5()
	{
	    $this->p->params->md5HashValue = "te";
		$this->setParams($this->p->params);
		$response = $this->request();
		$this->assertEquals('I', $this->order->get("status"));
		$this->assertTrue(ereg("MD5", $response));
	}
*/
	function setParams($params)
	{
        $pm = func_new("PaymentMethod");
		$pm = $pm->getInstanceByName("2checkout");
		$oldparams = $pm->get("params"); // store old params
		$pm->set("params", $params);
		$this->p->set("params", $params);
		$pm->update();
		return $oldparams;
	}

}
$suite = new PHPUnit_TestSuite("TwoCheckoutCom_processorTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
