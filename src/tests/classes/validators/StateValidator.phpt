<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "validators/StateValidator.php";

class StateValidatorTest extends PHPUnit_TestCase
{
    function StateValidatorTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function test_isValid()
    {
		$v = new StateValidator("state_id", "country", "error");
		$result = $v->isValid((object)array("state_id" => "10", "country" => "US"));
		$this->assertTrue($result);
		$result = $v->isValid((object)array("state_id" => "10", "country" => "CA"));
		$this->assertFalse($result);
		$this->assertEquals("error", $v->errorMessage);
		$result = $v->isValid((object)array("state_id" => "-1", "country" => "CA"));
		$this->assertTrue($result);
    }

}


$suite = new PHPUnit_TestSuite("StateValidatorTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
