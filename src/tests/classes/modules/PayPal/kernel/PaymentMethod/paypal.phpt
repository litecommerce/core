<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class PayPal_processorTest extends PHPUnit_TestCase
{
	var $p;
	var $order;
	
    function PayPal_processorTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->p =& func_new("PaymentMethod_PayPal");
		$params = array(
		    "login" => "test",
		    "url" => "https://rrf.ru/~ndv/x-lite/tests/classes/modules/PayPal/paypal.php");
        $pm =& func_get_instance("PaymentMethod");
		$this->p = func_get_instance("PaymentMethod", "paypal");
		$this->p->read();
		$this->oldparams = $this->p->get("params");
	    $this->p->set("params", $params);
		$this->p->update();
		$this->order = func_new("Order");
		$this->order->set("total", 155.55);
		$this->order->set("payment_method", "paypal");
		$this->order->set("profileCopy", func_new("Profile", 1)); // bit-bucket
		$this->order->create();
    }

    function tearDown()
    {
		$this->order->delete();
		$this->p->set("params", $this->oldparams);
		$this->p->update();
    }

	function request($invalid = 0, $payment_status = "Completed")
	{
		$https = func_new("HTTPS");
		$https->url = $this->p->get("params.url");
        $header =& func_get_instance("Header");
		$https->data = array(
			'cmd' => '_ext-enter',
			'redirect_cmd' => 'redirect_cmd',
			'business' => $this->p->get("params.login"),
			'invoice' => $this->order->get("order_id"),
			"amount" => $this->order->get("total"),
			"first_name" => $this->order->get("profile.billing_firstname"),
			"last_name" => $this->order->get("profile.billing_lastname"),
			"address1" => $this->order->get("profile.billing_address"),
			"city" => $this->order->get("profile.billing_city"),
			"state" => $this->order->get("profile.billing_state_code"),
			"zip" => $this->order->get("profile.billing_zipcode"),
			"day_phone_a" => $this->order->call("paymentMethod.getDayPhoneA", $this->order->get("profile")),
			"day_phone_b" => $this->order->call("paymentMethod.getDayPhoneB", $this->order->get("profile")),
			"day_phone_c" => $this->order->call("paymentMethod.getDayPhoneC", $this->order->get("profile")),
			"email" => $this->order->get("profile.login"),
			"item_name"=> $this->order->call("paymentMethod.getItemName", $this->order),
			"currency_code" => "USD",
			"return" => $header->getURL("cart.php?target=checkout&action=return&order_id=".$this->order->get("order_id")),
			"cancel_return" => $header->getURL("cart.php?target=checkout"),
			"notify_url" => $header->getURL("cart.php?target=checkout&action=callback&order_id=".$this->order->get("order_id")),
			"invalid" => $invalid,
			"payment_status" => $payment_status,
			"test_mode" => "1"
		);	
		$https->request();
        print "response = " . $https->response;
		$this->order =& func_new("Order", $this->order->get("order_id"));
		return $https->response;
	}
	
	function testFailure()
	{
		$resp = $this->request(0, "Failed");
		$this->assertEquals('F', $this->order->get("status"));
		if ($this->order->get("status") != 'F' ) {
			print $resp;
		}
	}

	function testInvalid()
	{
		$this->request(1);
		$this->assertEquals('I', $this->order->get("status"));
	}

	function testSuccess()
	{
		$this->request();
		$this->assertEquals('P', $this->order->get("status"));
	}

}
$suite = new PHPUnit_TestSuite("PayPal_processorTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
