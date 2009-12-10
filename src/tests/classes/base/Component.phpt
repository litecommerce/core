<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class ComponentTest extends PHPUnit_TestCase
{
    function ComponentTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->component = func_new('Component');
        $this->component->params = array('target', 'mode');
        $_REQUEST["target"] = "test";
        $_REQUEST["mode"] = "test mode";
        $this->component->init();
    }

    function testAddComponent()
    {
        $component = func_new('Component');
        $component->id = "123";
        $component->params = array('name', 'age');
        $_REQUEST["name"] = "John";
        $_REQUEST["age"] = "25";
        $component->init();
        $this->component->addComponent($component);
        $this->assertTrue($this->component->components[0]->id == "123");
    }

    function testGetUrl()
    {
        $this->testAddComponent();
        $this->assertTrue($this->component->get("url") == "cart.php?target=test&mode=test+mode&name=John&age=25");
    }
}


$suite = new PHPUnit_TestSuite('ComponentTest');
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
