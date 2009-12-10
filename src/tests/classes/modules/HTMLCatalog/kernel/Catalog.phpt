<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class CatalogTest extends PHPUnit_TestCase
{
    function CatalogTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
    }

    function tearDown()
    {
    }

    function testGetParentList()
    {
        $c =& func_new("Catalog");
        // Songbird, Eva Cassidy
        $url = "cart.php?target=product&product_id=81&category_id=105";
        $result = $c->getParentList($url);
        // parent is Home :: CD - DVD - Video  :: CD audio 
        // cart.php?target=category&category_id=105&pageID=1
        $this->assertEquals($result[0], "cart.php?target=category&category_id=105&pageID=1");
    }

    function testGetChildList()
    {
        $c =& func_new("Catalog");
        $url = "cart.php?target=category&category_id=243";
        $result = $c->getChildList($url);
        $this->assertFalse(empty($result));
    }

    function testGetFileNameByURL()
    {
        $c =& func_new("Catalog");
        $url = "cart.php?target=category&category_id=243";
        $result = $c->getFileNameByURL($url);
        $this->assertEquals($result, "category_243_Sport.html");
    }
}


$suite = new PHPUnit_TestSuite("CatalogTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
