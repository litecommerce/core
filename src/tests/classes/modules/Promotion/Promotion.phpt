<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class PromotionTest extends PHPUnit_TestCase
{
    function PromotionTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
    }

    function tearDown()
    {
    }

	function testPatchInvoice()
	{
	}
}


$suite = new PHPUnit_TestSuite("PromotionTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
