<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class DemoModeBaseTest extends PHPUnit_TestCase
{
    function DemoModeBaseTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

	function test_setSessionVar()
	{
		$base = func_new("Module_DemoMode_Base");
		$base->_setSessionVar(array("123", "234", "345"), "456");
		$this->assertEquals(array("123"=>array("234"=>array("345"=>"456"))), $base->session->get("safeData"));
	}

	function test_getSessionVar()
	{
		$base = func_new("Module_DemoMode_Base");
		$base->_setSessionVar(array("123", "234", "345"), "456");
		$this->assertEquals("456", $base->_getSessionVar(array("123", "234", "345")));
		$this->assertEquals(null, $base->_getSessionVar(array("asd", "234", "345")));
	}

	function test_config()
	{
		$c = func_new("Config");
		$c->constructor();
		$c->set("category","TEST");
		$c->set("name","test");
		$c->set("type","text");
		$value = rand();
		$c->set("value", $value);
		if ($c->isExists()) {
			$c->update();
		} else {
			$c->create();
		}
		$c->readConfig();

		$this->assertEquals($value, $c->config->get("TEST.test"));
		$v = $c->db->getOne("select value from xlite_config where name='test' and category='TEST'");
		$this->assertNotSame($value, $v);
	}
	
}


$suite = new PHPUnit_TestSuite("DemoModeBaseTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
