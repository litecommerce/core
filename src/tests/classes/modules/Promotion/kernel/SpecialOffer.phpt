<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class SpecialOfferTest extends PHPUnit_TestCase
{
    function SpecialOfferTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        global $xlite;
		$c = & $xlite->db;
/*		$c->query("delete from xlite_special_offers");
		$c->query("delete from xlite_special_offer_bonusprices");
		$c->query("delete from xlite_special_offer_products");
*/	
		$this->so = func_new("SpecialOffer");
		// create a special offer
		$this->so->set("properties", array(
			"date" => time(),
			"title" => "Title",
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
		$this->so->create();
    }

    function tearDown()
    {
		if ($this->so->isPersistent) {
			$this->so->delete();
		}
    }

	function test_addProduct()
	{
		$this->so->addProduct(func_new("Product", 121), 'C');
		$this->assertEquals(1, count($this->so->get("products")));
		$this->assertEquals(121, $this->so->products[0]->get("product_id"));
		$this->so->addProduct(func_new("Product", 121), 'C');
		$this->assertEquals(1, count($this->so->get("products")));
		$this->so->addProduct(func_new("Product", 121), 'B');
		$this->so->addProduct(func_new("Product", 128), 'B');
		$this->assertEquals(1, count($this->so->get("products")));
		$this->assertEquals(2, count($this->so->get("bonusProducts")));
		// get bonus products
		$bp = array();
		foreach($this->so->get("bonusProducts") as $p) 
            $bp[] = $p->get("product_id");
		array_multisort($bp);
		$this->assertEquals(array("121","128"), $bp);
	}

	function test_deleteProduct()
	{
		$this->so->addProduct(func_new("Product", 121), 'C');
		$this->so->addProduct(func_new("Product", 121), 'B');
		$this->so->deleteProduct(func_new("Product", 121), 'C');
		$this->assertEquals(1, count($this->so->get("bonusProducts")));
		$this->assertEquals(0, count($this->so->get("products")));
		$this->assertEquals(121, $this->so->bonusProducts[0]->get("product_id"));
	}

	function test_addBonusPrice()
	{
		$this->so->addBonusPrice(func_new("Product", 121),null);
		$this->assertEquals(1, count($this->so->get("bonusPrices")));
		$this->assertEquals(180.5, $this->so->bonusPrices[0]->get("price"));
		$this->so->deleteBonusPrice(func_new("Product", 121),null);
		$this->so->addBonusPrice(func_new("Product", 128), null, 333);
		$this->assertEquals(1, count($this->so->get("bonusPrices")));
		$this->assertEquals(333, $this->so->bonusPrices[0]->get("price"));
	}
	function test_delete()
	{
        global $xlite;
		$cnn = & $xlite->db;
		$a = $cnn->getOne("select count(*) from xlite_special_offers");
		$b = $cnn->getOne("select count(*) from xlite_special_offer_products");
		$c = $cnn->getOne("select count(*) from xlite_special_offer_bonusprices");
		$this->so->addBonusPrice(func_new("Product", 121), null);
		$this->so->addProduct(func_new("Product", 121), 'C');
		$this->so->addProduct(func_new("Product", 121), 'B');
		$this->so->delete();
		$this->assertEquals($a-1, $cnn->getOne("select count(*) from xlite_special_offers"));
		$this->assertEquals($b, $cnn->getOne("select count(*) from xlite_special_offer_products"));
		$this->assertEquals($c, $cnn->getOne("select count(*) from xlite_special_offer_bonusprices"));
		
	}
	
	function test_updateProperties()
	{
		$this->test_addProduct();
		$so = func_new("SpecialOffer", $this->so->get("offer_id"));
		// check fields
		$this->assertEquals(128, $so->get("product.product_id"));
		$this->assertEquals(75, $so->get("category.category_id"));
	}

	function test_checkCondition()
	{
		$so = func_new("SpecialOffer");
		$so->set("properties", array(
			"conditionType" => "productAmount",
			"product_id" => 128,
			"category_id" => 3,  // books
			"amount" => 5
			));
		$so->create();

		$order = func_new("Cart");
		$order->create();
		$oi = func_new("OrderItem");
		$oi->setProduct(func_new("Product", 128));
		$oi->set("amount", 3);
		$order->addItem($oi);
		$this->assertFalse($so->checkCondition($order));

		$oi1 = func_new("OrderItem");
		$oi1->setProduct(func_new("Product", 16126));
		$oi1->set("amount", 3);
		$order->addItem($oi1);
		$this->assertFalse($so->checkCondition($order));

		$oi2 = func_new("OrderItem");
		$oi2->setProduct(func_new("Product", 69)); // book
		$oi2->set("amount", 3);
		$order->addItem($oi2);
		$this->assertTrue($so->checkCondition($order));

		$so->set("conditionType", "orderTotal");
		$so->set("amount", 100);
        $order->calcTotals();
		$this->assertTrue($so->checkCondition($order));
		$so->set("amount", 1000);
		$this->assertFalse($so->checkCondition($order));

		$so->set("conditionType", "productSet");
		$so->addProduct(func_new("Product", 128));
		$this->assertTrue($so->checkCondition($order));
		$so->addProduct(func_new("Product", 16126));
		$this->assertTrue($so->checkCondition($order));
		$so->addProduct(func_new("Product", 10));
		$this->assertFalse($so->checkCondition($order));

		$profile = func_new("Profile", 1);
		$profile->set("bonusPoints", 50);
		$order->set("profile", $profile);
		$so->set("conditionType", "bonusPoints");
		$so->set("amount", 40);
        $order->calcTotals();
		$this->assertTrue($so->checkCondition($order));
		$so->set("amount", 60);
		$this->assertFalse($so->checkCondition($order));
		
		$so->delete();
		$order->delete();
	}

	function testgetBonusPrice()
	{
		$so = func_new("SpecialOffer");
		$so->set("properties", array(
			"conditionType" => "productAmount",
			"product_id" => 128,
			"category_id" => 3,  // books
			"amount" => 5,
			"bonusType" => "discounts",
			"bonusAmount" => 5,
			"bonusAmountType" => "%"
			));
		$so->create();
		$so->addProduct(func_new("Product", 128), 'B');

		$this->assertEquals(11.875, $so->getBonusPrice(func_new("Product", 128), 12.50));
		$this->assertEquals(1450, $so->getBonusPrice(func_new("Product", 129), 1450));
		$so->set("bonusType", "specialPrices");
		$so->addBonusPrice(func_new("Product", 128), null, 10);
		$so->addBonusPrice(func_new("Product",129), null, 1000);
		$this->assertEquals(10, $so->getBonusPrice(func_new("Product",128), 12.50));
		$this->assertEquals(1000, $so->getBonusPrice(func_new("Product",129), 1450));

		$so->delete();
	}

	function testClone()
	{
		$so = func_new("SpecialOffer");
		$so->set("properties", array(
			"conditionType" => "productAmount",
			"product_id" => 128,
			"category_id" => 3,  // books
			"amount" => 5
			));
		$so->create();
		$so->addBonusPrice(func_new("Product",128), null, 5);
		$so->addProduct(func_new("Product",129), 'B');
		$so->addProduct(func_new("Product",130), 'C');
		$so1 = $so->clone();
		// compare
		$this->assertEquals(1, count($so1->get("bonusPrices")));
		$this->assertEquals(128, $so1->bonusPrices[0]->get("product_id"));
		$this->assertEquals(5, $so1->bonusPrices[0]->get("price"));
		$this->assertEquals(1, count($so1->get("bonusProducts")));
		$this->assertEquals(129, $so1->bonusProducts[0]->get("product_id"));
		$this->assertEquals(1, count($so1->get("products")));
		$this->assertEquals(130, $so1->products[0]->get("product_id"));
		$so1->delete();
		$so->delete();
	}

	function test_excludeNonConditionalProducts_byproduct()
	{
		$so = func_new("SpecialOffer");
		$so->products = array(func_new("Product",128));
		$so->bonusProducts = array(func_new("Product",128), func_new("Product",129));

		$this->assertTrue($so->excludeNonConditionalProducts());
		$this->assertEquals(1, count($so->bonusProducts));
		$this->assertEquals(128, $so->bonusProducts[0]->get("product_id"));
	}
	function test_excludeNonConditionalProducts_bycategory()
	{
		$so = func_new("SpecialOffer");
		$so->category = func_new("Category",3);
		$so->bonusCategory = func_new("Category",24);

		$this->assertTrue($so->excludeNonConditionalProducts());
		$this->assertEquals(24, $so->bonusCategory->get("category_id"));

		$so = func_new("SpecialOffer");
		$so->category = func_new("Category",24);
		$so->bonusCategory = func_new("Category",3);

		$this->assertTrue($so->excludeNonConditionalProducts());
		$this->assertEquals(24, $so->bonusCategory->get("category_id"));
	}
	function test_excludeNonConditionalProducts_mixed()
	{
		$so = func_new("SpecialOffer");
		$so->category = func_new("Category",3);
		$bp1 = func_new("BonusPrice");
		$bp1->product = func_new("Product",10);
		$bp2 = func_new("BonusPrice");
		$bp2->product = func_new("Product",123123);

		$so->bonusPrices = array($bp1, $bp2);

		$this->assertTrue($so->excludeNonConditionalProducts());
		$this->assertEquals(1, count($so->bonusPrices));
		$this->assertEquals(10, $so->bonusPrices[0]->product->get("product_id"));
		$this->assertFalse($so->bonusProducts);

		$so = func_new("SpecialOffer");
		$so->category = func_new("Category",24);
		$so->bonusCategory = func_new("Category",244);

		$this->assertFalse($so->excludeNonConditionalProducts());
	}

}

$suite = new PHPUnit_TestSuite("SpecialOfferTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
