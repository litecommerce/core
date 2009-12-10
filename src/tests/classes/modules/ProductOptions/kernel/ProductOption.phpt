<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "modules/ProductOptions/ProductOptions.php";
require_once "modules/ProductOptions/kernel/ProductOption.php";

class ProductOptionTest extends PHPUnit_TestCase
{
    var $po;

    function ProductOptionTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->po =& new ProductOption();
        $this->drop();
        $this->create();
    }

    function tearDown()
    {
        $this->drop();
    }

    function create()
    {
        $sql = "insert into xlite_product_options (option_id, product_id, optclass, opttext, options, orderby) values (10000, 67, 'Color', 'Choose color', 'Red=-4%\nBlue=+5\nBrown', 10)";
        $this->po->connection->query($sql);
    }
    function drop()
    {
        $this->po->connection->query("delete from xlite_product_options where option_id=10000");
    }
    
    function testGetOptions()
    {
        $this->po->set("option_id", 10000);
        $options = $this->po->getOptions();
        $this->assertFalse(empty($options));
    }
}

$suite = new PHPUnit_TestSuite("ProductOptionTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
