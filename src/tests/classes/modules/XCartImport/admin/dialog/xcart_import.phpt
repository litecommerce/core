<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "modules/XCartImport/admin/dialog/xcart_import.php";

class Admin_Dialog_xcart_importTest extends PHPUnit_TestCase
{
    function Admin_Dialog_xcart_importTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->xcart_import = new Admin_Dialog_xcart_import;
    }

    function tearDown()
    {
    }

	function test_childrenFromAssoc()
	{
		$assoc = array("asd" => "dsa", "sdf" => "fds");
		$children = array(
			array("value" => "asd", "children" => array(array("value" => "dsa", "children" => array()))),
			array("value" => "sdf", "children" => array(array("value" => "fds", "children" => array()))));
		$this->assertEquals($children, $this->xcart_import->childrenFromAssoc($assoc));
		$this->assertEquals($assoc, $this->xcart_import->childrenToAssoc($children));
	}
	
	function test_saveRow()
	{
		$this->xcart_import->category_links = array(106, 106);
		$this->xcart_import->saveRow(array("sku" => "SOCBALL01", "name" => "Brine ATTACK Soccer Ball *", "product_id" => 1111111), "product");
		$p = new Product;
		$p->find("sku='SOCBALL01'");
		$this->assertEquals("Brine ATTACK Soccer Ball *", $p->get("name"));
		$this->assertEquals(array("106","243"), $this->ids($p->getCategories()));

		$this->xcart_import->saveRow(array("sku" => "ASD", "name" => "Brine ATTACK Soccer Ball *", "product_id" => 1111111), "product");
		$this->xcart_import->category_links = array(106, 106);
		$p = new Product;
		$this->assertEquals(2, count($p->findAll("name='Brine ATTACK Soccer Ball *'")));
	}

	function test_mapData()
	{
		$data = array(
			"value" => "root",
			"children" => array(
				array("value" => "first", "children" => array()),
				array("value" => "second", "children" => array(
					array("value" => "subsecond", "children" => array()),
					array("value" => "subsecond2", "children" => array()),
				))));
		$data1 = $data;
		$this->xcart_import->map = array(
			"root" => array(
				"first" => "1st",
				"second" => "2nd"),
			"root.2nd.subsecond" => "sub2nd");
		$this->xcart_import->mapData($data, "root");
		$data1["children"][0]["value"] = "1st";
		$data1["children"][1]["value"] = "2nd";
		$data1["children"][1]["children"][0]["value"] = "sub2nd";
		$this->assertEquals($data1, $data);
	}

	function ids($categories)
	{
		$ids = array();
		foreach($categories as $c) {
			$ids[] = $c->get("category_id");
		}
		return $ids;
	}
}


$suite = new PHPUnit_TestSuite("Admin_Dialog_xcart_importTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
