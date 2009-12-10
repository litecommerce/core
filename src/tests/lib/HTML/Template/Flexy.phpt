<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "HTML/Template/Flexy.php";

class FlexyTest extends PHPUnit_TestCase
{
    var $module;

    function ModuleTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
    }

    function tearDown()
    {
    }

	function check_parser($file_prefix) 
	{
        $this->flexy = new HTML_Template_Flexy;
		$this->flexy->currentTemplate = "tests/lib/HTML/Template/$file_prefix.in";
		$this->flexy->compiledTemplate = "tests/lib/HTML/Template/$file_prefix.out";
		$this->flexy->_parse();
		$str = trim(file_get_contents($this->flexy->compiledTemplate));
		$model = trim(file_get_contents("tests/lib/HTML/Template/$file_prefix.model"));
		$this->assertEquals($str."\n\n", $model."\n\n");
	}

	/*
	* Check if checked= attribute is handled properly
	*/
    function test_checked_attribute()
    {
		$this->check_parser("FlexyCheckedAttribute");
    }
	/*
	* Check if selected= attribute is handled properly
	*/
    function test_selected_attribute()
    {
		$this->check_parser("FlexySelectedAttribute");
    }
	/*
	* Check if code= attribute is handled properly
	*/
    function test_code_attribute()
    {
		$this->check_parser("FlexyCodeAttribute");
    }

}


$suite = new PHPUnit_TestSuite("FlexyTest");
$result = PHPUnit::run($suite);

?>
