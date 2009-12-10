<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

require_once "tests/classes/config.php";
//require_once "tests/config.php";
require_once "PHPUnit.php";
require_once "kernel/Catalog.php";

class CatalogTest extends PHPUnit_TestCase
{
    var $catalog;
    var $url_prefix;

    function ModuleTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        global $options;
        $this->url_prefix = "http://".
                            $options["host_details"]["http_host"] . 
                            $options["host_details"]["web_dir"] .
                            "/";
        
        $this->catalog = new Catalog();
    }

    function tearDown()
    {
        unset($this->catalog);
    }

	/**
	* Test functions
	*/
	function testRetreiveProductLink()
	{
		$product = new Product(92);
        $category = new Category(131);
        
        $expected = $this->url_prefix . CART_SELF . "?target=product&action=view&product_id=" . $product->get("product_id") . "&category_id=" . $category->get("category_id");
        
		$result = $this->catalog->retreiveProductLink($product, $category);
		$this->assertEquals($expected, $result);
	}

    function testRetreiveCategoryLink()
    {
        $category = new Category(62);
        $expected = $this->url_prefix . CART_SELF . "?target=category&action=view&category_id=" . $category->get("category_id");
        $result = $this->catalog->retreiveCategoryLink($category);
        $this->assertEquals($expected, $result);
    } 

	function testCreateProductFileName()
	{
		$product = new Product(92);
        $category = new Category(131);
		$filename = "product_92_Cranium_131.html";
		$result = $this->catalog->createProductFileName($product,$category);
		$this->assertEquals($filename, $result);
	}

	function testCreateCategoryFileName()
	{
		$category = new Category(62);
		$filename = "category_62_Household.html";
		$result = $this->catalog->createCategoryFileName($category);
		$this->assertEquals($filename, $result);
	}
}

$suite = new PHPUnit_TestSuite("CatalogTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
