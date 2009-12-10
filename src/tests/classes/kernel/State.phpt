<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "classes/kernel/State.php";

class StateTest extends PHPUnit_TestCase
{
    var $state;

    function StateTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->state = new State;
    }

    function tearDown()
    {
        unset($this->state);
    }

    function testReadAll()
    {
        $result = $this->state->readAll();
        $this->assertTrue(count($result)>0);
    }
}


$suite = new PHPUnit_TestSuite("StateTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
