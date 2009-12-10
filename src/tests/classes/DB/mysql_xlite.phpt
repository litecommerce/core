<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "DB/mysql_xlite.php";

class mysql_xliteTest extends PHPUnit_TestCase
{
    function mysql_xliteTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function testisFieldExists()
    {
		$connection =& Database::getConnection();
		$this->assertTrue($connection->isFieldExists("xlite_products", "thumbnail_source"));
		$this->assertFalse($connection->isFieldExists("xlite_products", "Thumbnail_source"));
    }

	function test_isIndexExists()
	{
		$connection =& Database::getConnection();
		$this->assertTrue($connection->isIndexExists("xlite_product_links_product", "xlite_product_links"));
		$this->assertFalse($connection->isIndexExists("xlite_product", "xlite_product_links"));
	}
}


$suite = new PHPUnit_TestSuite("mysql_xliteTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
