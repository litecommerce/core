<?php
require_once "tests/classes/config.php";
require_once "PHPUnit.php";
ini_set("include_path", "tests/classes/kernel/ModulesManager".PATH_SEPARATOR.ini_get("include_path"));
require_once "BaseClass.php";
require_once "BaseClass_Module1.php";
require_once "ChildClass.php";
require_once "ChildClass_Module1.php";
require_once "ChildClass_Module2.php";

class ModulesManagerTest extends PHPUnit_TestCase
{
    var $manager;

    function ModulesManagerTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->manager =& ModulesManager::getInstance();
    }

    function tearDown()
    {
    }


    function testinitModules()
    {
	    // read modules from database
        $result = $this->manager->initModules();
        $this->assertFalse(PEAR::isError($result), PEAR::isError($result) ?
                                                    $result->getMessage : "");
    }

    function testGetInstanceOf()
    {
		$m1 =& new module1;
		$m1->init();
		$m2 =& new module2;
		$m2->init();
		$this->manager->activeModules = array($m1, $m2);
        $result =& new ChildClass("param1_value");
        $this->assertEquals("childclass_module2__ childclass_module1__ childclass__ baseclass_module1__ baseclass object", $result->_parentList());
		// check constructor behaviour
		$this->assertEquals("param1_value", $result->param1);
		$this->assertEquals("asd", $result->param2);
        $result =& new ChildClass("param1_value", "param2_value");
		$this->assertEquals("param1_value", $result->param1);
		$this->assertEquals("param2_value", $result->param2);
    }
}

class module1 extends Module
{
	function init()
	{
		parent::init();
		$this->addDecorator("BaseClass", "BaseClass_Module1");
		$this->addDecorator("ChildClass", "ChildClass_Module1");
	}
}

class module2 extends Module
{
	function init()
	{
		parent::init();
		$this->addDecorator("ChildClass", "ChildClass_Module2");
	}
}

$suite = new PHPUnit_TestSuite("ModulesManagerTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
