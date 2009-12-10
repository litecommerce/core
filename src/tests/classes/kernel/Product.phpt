<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class ProductTest extends PHPUnit_TestCase
{
    var $product;

    function ProductTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->product = func_new("Product", 92);
    }

    function tearDown()
    {
        unset($this->product);
    }

    function testCategories()
    {
        $c = $this->product->getCategories();
        $this->assertTrue(count($c)==1, "Wrong count of categories for Product(92)- ".count($c).", must be 1");
        $this->assertEquals($c[0]->get("name"), "Board games");
        $p = func_new("Product");
        $this->assertTrue(count($p->getCategories())==0);
    }

    function testAdvancedSearch()
    {
        $products = $this->product->advancedSearch('Device', '','','');
        $this->assertNames(array("HEWLETT PACKARD PHOTOSMART 618", "Psion Netbook","SONY MCV-CD200(CDRW)"), $products);

        $products = $this->product->advancedSearch('', 'B','','',true);
        $this->assertNames(array("Dreamweaver 3 Bible : Gold Edition", "The Swan Barbie"), $products);
        $products = $this->product->advancedSearch('', 'BBB','','',true);
        $this->assertNames(array(), $products);
    }
    function assertNames($model, &$products)
    {
        $names = array();
        foreach ($products as $p) {
            $names[] = trim($p->get("name"));
        }
        return $this->assertEquals($model, $names);
    }
    function testClone()
    {
        $p = func_new("Product", 67);
        $new = $p->clone();
        $this->assertTrue(strstr($new->sql, 'Designing Web Usability'));
    }

    function testinCategory()
    {
        $p = func_new("Product", 67);
        $p->addCategory(func_new("Category", 62));
        $this->assertTrue($p->inCategory(func_new("Category",23)));
        $this->assertTrue($p->inCategory(func_new("Category",62)));
        $this->assertFalse($p->inCategory(func_new("Category",0)));
        $this->assertFalse($p->inCategory(func_new("Category",243)));
    }
}

$suite = new PHPUnit_TestSuite("ProductTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
