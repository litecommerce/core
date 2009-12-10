<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class OrderTest extends PHPUnit_TestCase
{
    function OrderTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        global $xlite;
		$c = & $xlite->db;
		$c->query("delete from xlite_special_offers");
		$c->query("delete from xlite_special_offer_bonusprices");
		$c->query("delete from xlite_special_offer_products");
    }

    function tearDown()
    {
    }

	function testPayByPoints()
	{
		global $xlite;
		$xlite->config->Promotion->payByBonusPoints = 1;
		$xlite->config->Promotion->bonusPointsCost = 0.1;
		
		$profile =& func_new("Profile",1); // bit-bucket
		$auth =& func_get_instance("Auth");
		$auth->loginProfile($profile);
		$profile->set("bonusPoints", 500);
		$profile->update();
		$order =& func_get_instance("Cart");
		$order->set("orig_profile_id", 1);
		$order->set("status", "T");
		$order->set("payedByPoints", 50);
		$order->create();
		$oi = func_new("OrderItem");
		$product = func_new("Product",128); // FIRSTLINE 2116 Cordon Audio-Stereo
		$oi->set("product", $product);
		$oi->set("amount", 10);
		$order->addItem($oi);
		$order->checkout();
		$order->read();

		// before checkout
        $order->calcTotals();
		$total = $product->get("price")*10 + $order->get("tax") + $order->get("shippingCost");
		$this->assertEquals($total - 50, $order->get("total"));
		// after checkout
		$order->set("status", "Q");
        $order->calcTotals();
		$order->update();
		$this->assertEquals($total - 50, $order->get("total"));
		$profile = func_new("Profile",1);
		$this->assertEquals(0, $profile->get("bonusPoints"));
		// order failed
		$order->set("status", "D");
		$order->update();
		$profile = func_new("Profile",1);
		$this->assertEquals(500, $profile->get("bonusPoints"));
	}
	
	function test_getSpecialOffers()
	{
		$so = func_new("SpecialOffer");
		// create a special offer
		$so->set("properties", array(
			"date" => time(),
			"title" => "Offer 1",
			"conditionType" => "productAmount",
			"product_id" => 128, // FIRSTLINE 2116 Cordon Audio-Stereo
			"category_id" => 75,
			"bonusType" => "discounts",
			"amount" => 5, // items
			"bonusAmount" => 10 , // 10
			"bonusAmountType" => '%' , // %
			"bonusAllProducts" => 0,
			"bonusCategory_id" => 108, // computer hardware
			)
		);
		$so->create();
		$so->set("conditionType", "orderTotal");
		$so->set("amount", 1000);
		$so->set("title", "Offer 2");
		$so1 = $so->clone();

		$profile =& func_new("Profile",1); // bit-bucket
		$auth =& func_get_instance("Auth");
		$auth->loginProfile($profile);
		$profile->set("bonusPoints", 500);
		$profile->update();
		$order =& func_new("Order");
		$order->set("orig_profile_id", 1);
		$order->set("profile_id", 1);
		$order->set("status", "T");
		$order->set("date", time());
		$order->create();
		$oi = func_new("OrderItem");
		$product = func_new("Product",128); // FIRSTLINE 2116 Cordon Audio-Stereo
		$oi->set("product", $product);
		$oi->set("amount", 5);
		$order->addItem($oi);
		
		$offers = $order->get("specialOffers");
		$this->assertEquals(1, count($offers)); // first only
		$this->assertEquals("Offer 1", $offers[0]->get("title")); // first only
		$oi =& $order->get("items.0");
		$oi->set("amount", 1000);
        $oi->update();

		$offers = $order->get("specialOffers");
		$this->assertEquals(2, count($offers));

		$order->delete();
		$so->delete();
		$so1->delete();
	}
}


$suite = new PHPUnit_TestSuite("OrderTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
