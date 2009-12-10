<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class LayoutTest extends PHPUnit_TestCase
{
    var $layout;

    function LayoutTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->layout =& func_get_instance("Layout");
    }

    function tearDown()
    {
        unset($this->layout);
    }

    function testAddLayout()
    {
        $this->layout->addLayout("widget", "template");
        $this->assertTrue(isset($this->layout->list["widget"]));
    }

    function testGetLayout()
    {
        $layout = $this->layout->getLayout("widget");
        $this->assertFalse(PEAR::isError($layout) || empty($layout));
    }

	function testGetSkins()
	{
		$this->assertEquals(array("default"), $this->layout->getSkins());
		$this->assertEquals(array("admin", "default"), $this->layout->getSkins(true));
	}

	function testGetLocales()
	{
		$this->assertEquals(array("en"), $this->layout->getLocales('default'));
	}
}


$suite = new PHPUnit_TestSuite("LayoutTest");
$result = PHPUnit::run($suite);

?>
