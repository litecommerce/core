<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class BaseTest extends PHPUnit_TestCase
{
    var $base;

    function BaseTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->base = func_new("Base");
		$this->base->alias = "products";
		$this->base->fields = array("a"=>"b", "id"=>12, "prop"=>"");
		$this->base->setProperties(array("a"=>"b", "id"=>12));
		$this->base->autoIncrement = "id";
		$this->base->primaryKey = array("id");
    }

    function tearDown()
    {
		$this->base->db->query("delete from xlite_products where name='test product'");
        unset($this->base);
    }
	
	function testBuildInsert()
	{
		$sql = $this->base->_buildInsert();
		$this->assertEquals("INSERT INTO xlite_products (a) VALUES ('b')\n","$sql\n");
	}

	function testBuildSelect()
	{
		$sql = $this->base->_buildSelect("a='b'");
		$this->assertEquals("SELECT a,id,prop FROM xlite_products WHERE a='b'\n","$sql\n");
		$sql = $this->base->_buildSelect();
		$this->assertEquals("SELECT a,id,prop FROM xlite_products\n","$sql\n");
	}

	function testBuildSelectOnlyKeys()
	{
		$this->base->fetchKeysOnly = true;
		$sql = $this->base->_buildSelect("a='b'");
		$this->assertEquals("SELECT id FROM xlite_products WHERE a='b'\n","$sql\n");
		$sql = $this->base->_buildSelect();
		$this->assertEquals("SELECT id FROM xlite_products\n","$sql\n");
		$this->base->fetchKeysOnly = false;
	}

	function testBuildRead()
	{
		$sql = $this->base->_buildRead();
		$this->assertEquals("SELECT a,id,prop FROM xlite_products WHERE id='12'\n","$sql\n");
	}

	function testBuildUpdate()
	{
		$sql = $this->base->_buildUpdate();
		$this->assertEquals("UPDATE xlite_products SET a='b' WHERE id='12'\n","$sql\n");
	}
	
	function &initObject()
	{
		$base = func_new("Product");
		$base->properties = array("name"=>"test product", "description"=>"descr'",  "sku"=>"");
		return $base;
	}
	
	function testCreate()
	{
		$this->base =& $this->initObject();
		$this->base->setProperties(array("name"=>"test product", "description"=>"descr'"));
		$this->base->create();
		$id = $this->base->get("product_id");
		$this->assertFalse($id==12345, "product_id was not set");
		$s = $this->base->db->getOne("select count(*) from xlite_products where name='test product'");
		$this->assertTrue($s, "Record was not found in the table");
	}

	function testUpdate()
	{
		$this->testCreate(); // create a product;
		$this->base->set("sku", "SKU");
		$this->base->update();
		$s = $this->base->db->getOne("select sku from xlite_products where name='test product'");
		$this->assertEquals("SKU", $s, "Record was not updated");
	}

	function testBuildDelete()
	{
		$sql = $this->base->_buildDelete();
		$this->assertEquals("DELETE FROM xlite_products WHERE id='12'", $sql);
	}

	function testDelete()
	{
		$this->testCreate(); // create a product;
		$this->base->delete();
		$s = $this->base->db->getOne("select count(*) from xlite_products where name='test product'");
		$this->assertFalse($s, "Couldn't delete the record");
	}

	function testClone()
	{
		$p1 = $this->initObject();
		$p1->set("product_id", null);
		$p1->create();
		$p2 =& $p1->clone();
		$this->assertTrue($p2->get("product_id") >= $p1->get("product_id"));
		$this->assertEquals($p1->get("name"), $p2->get("name"));
		$p1->delete();
		$p2->delete();
	}
	function testIterate()
	{
		$p = $this->initObject();
		$result = $p->iterate("name like '%ase%'", "name");
		$this->assertTrue($result, "Result is empty");
		$names = array("HP LaserJet 1200 + cable 2m", "HP LaserJet 2100M + cable 2m", "HP LaserJet 2200D + cable 2m");
		$returned = array();
		while ($p->next($result)) {
			$returned[] = $p->get("name");
		}
		$this->assertEquals($names, $returned);
		$p = $this->initObject();
		$result = $p->iterate("name like '%asd%'", "name");
		$this->assertFalse($result, "Result is notempty");
		$this->assertFalse($p->next($result));
	}

    function test_unslashProperties()
    {
        $base = func_new("Base");
        $p =array("cat"=>"\"\"\"asdf fdsa\"");
        $base->_unslashProperties($p);
        print_r($p);
    }
}

$suite = new PHPUnit_TestSuite("BaseTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";


?>
