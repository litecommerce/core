<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class CategoryTest extends PHPUnit_TestCase
{
    var $category;

    function CategoryTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->category = func_new("Category",62);
    }

    function tearDown()
    {
        unset($this->category);
    }

    function testSubcategories()
    {
        $subcat =& $this->category->getSubcategories();
        $this->assertTrue(count($subcat)==6, "Wrong count of items, must be 6");
        $names = array("Vacuum cleaners", "Cookers", "Furnaces", "Washing machines", "Microwaves", "Ustensils");
        foreach ($subcat as $c) {
            $this->assertTrue(array_search($c->get("name"), $names)!==false, "Category ".$c->get("name")." was not found");
        }
    }
    function testProducts()
    {
        $products = $this->category->getProducts();
        $this->assertTrue(count($products)==0, "Wrong count of products for catid=62, must be 0");
        $this->category->set("category_id", 63);
        $products = $this->category->getProducts();
        $this->assertTrue(count($products)==2, "Wrong count of products for catid=63, must be 2");
        $names = array("Vacuum cleaner", "Electrolux vacuum-cleaner");
        foreach ($products as $p) {
            $this->assertTrue(array_search($p->get("name"), $names)!==false, "Cant find product ".$p->get("name")." in category 63");
        }
    }

    function testCreateDelete()
    {
        $category =& func_new("Category");
        $category->set("name", "test category");
        $category->create();

        $category1 =& func_new("Category");
        $category1->set("name", "test category1");
        $category1->set("parentCategory", $category);
        $category1->create();

        $category2 =& func_new("Category");
        $category2->set("name", "test category2");
        $category2->set("parent", $category->get("category_id"));
        $category2->create();


        $product =& func_new("Product");
        $product->set("name", "test product");
        $product->set("price", "10");
        $product->create();
        $product->addCategory($category);
        $product->addCategory($category1);

        // check that the category exists
        $top =& func_new("Category");
        $top =& $top->getTopCategory();
        $categories = $top->getSubcategories();
        $found = false;
        foreach ($categories as $c) {
            if ($c->get("name") == $category->get("name")) {
                $found = true;
            }
        }
        $this->assertTrue($found, "Category was not found");

        // check categories of the product
        $categories = $product->getCategories() ;
        $found = false;
        foreach ($categories as $c) {
            if ($c->get("name") == $category->get("name")) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "The first Category was not found");

        $found = false;
        foreach ($categories as $c) {
            if ($c->get("name") == $category1->get("name")) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "The second category was not found");
        
        // remove the category1
        $category1->delete();

        // search for the category1
        $categories = $category->getSubcategories();
        $found = false;
        foreach ($categories as $c) {
            if ($c->get("name") == $category1->get("name")) {
                $found = true;
                break;
            }
        }
        $this->assertFalse($found, "The second category was not removed");
        
        $categories = $product->getCategories(null, null, false);
        $found = false;
        foreach ($categories as $c) {
            if ($c->get("name") == $category1->get("name")) {
                $found = true;
                break;
            }
        }
        $this->assertFalse($found, "The second category was not removed from the product links");

        $category->delete();

        // check that the category2 exists
        $top =& func_new("Category");
        $top =& $top->getTopCategory();
        $categories = $top->getSubcategories();
        $found = false;
        foreach ($categories as $c) {
            if ($c->get("name") == $category2->get("name")) {
                $found = true;
                break;
            }
        }
        $this->assertFalse($found, "Category2 was found");

        // search for the product
        $found = false;
        foreach ($product->findAll() as $p) {
            if ($p->get("name")==$product->get("name")) {
                $found = true;
                break;
            }
        }    
        $this->assertFalse($found, "The product was not removed");
    }

    function testcreateRecursive()
    {
        $cats = "Category One/Category Two/It's a last test category";
        $c = func_new("Category");
        $c->createRecursive($cats);
        $cat = func_new("Category");
        $found = $cat->find("name='Category One'");
        $this->assertTrue($found, "Category 1 was not found");
        $id = $cat->get("category_id");
        $cat = func_new("Category");
        $this->assertTrue($cat->find("name='Category Two'") && $cat->get("parent") == $id, "Category 2 was not found");

        // cleanup
        $cat = func_new("Category");
        if ($cat->find("name='Category One'")) {
            $cat->delete();
        }
    }

    function test_CategoriesByMembership()
    {
        $cat = func_new("Category",244);;
        $name = $cat->get("name"); // Men Clothes 
        $cat1 = func_new("Category");
        $this->assertTrue($cat1->find("name='".addslashes($name)."'"));
        
        $cat->set("membership", "Wholesale");
        $cat->update();
        $cat1 = func_new("Category");
        $this->assertFalse($cat1->find("name='".addslashes($name)."'"));
        
        $profile = func_new("Profile", 1); // bit-bucket@x-cart.com - admin!
        $profile->set("membership", "Wholesale");
        $profile->update();
        $cat->auth->loginProfile($profile);
        $cat1 = func_new("Category");
        $this->assertTrue($cat1->find("name='".addslashes($name)."'"));

        $profile->set("membership", "");
        $profile->update();
        $profile = func_new("Profile");
        $profile->set("login", "asd@dsa");
        $profile->create();
        $cat->auth->loginProfile($profile);
        $cat1 = func_new("Category");
        $result = $cat1->find("name='".addslashes($name)."'");
        $this->assertFalse($result, "Found category # ".$cat1->get("category_id"). " with membership ".$cat1->get("membership"));

        $profile->set("membership", "Wholesale");
        $profile->update();
        $cat->auth->loginProfile($profile);
        $cat1 = func_new("Category");;

        $result = $cat1->find("name='".addslashes($name)."'");
        $this->assertTrue($result);

        $profile->delete();
        $cat->set("membership", "%");
        $cat->update();
    }

    function test_parseCategoryField()
    {
        $cat = func_new("Category");
        $list = $cat->parseCategoryField("1/asd//dsa|xxx", true);
        $this->assertEquals(array(array("1", "asd/dsa"), array("xxx")), $list);
        $list = $cat->parseCategoryField("1/asd//dsa||", true);
        $this->assertEquals(array(array("1", "asd/dsa|")), $list);
    }

    function test_createCategoryField()
    {
        $cat = func_new("Category");
        $this->assertEquals("Household/Vacuum cleaners|Men Clothes", $cat->createCategoryField(array(func_new("Category", 63), func_new("Category", 244))));
    }
}

$suite = new PHPUnit_TestSuite("CategoryTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
