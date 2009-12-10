<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once CLASS_TO_TEST;

class TEST extends PHPUnit_TestCase
{
    function TEST($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
    }

    function tearDown()
    {
    }
}


$suite = new PHPUnit_TestSuite(TEST);
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
