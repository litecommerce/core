<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "classes/admin/Builder.php";

class BuilderTest extends PHPUnit_TestCase
{
	var $builder;

	function setUp()
	{
		$this->builder = new Builder();
		$this->builder->_initializeHTMLBuilder();
		$this->builder->_initializeTemplateBuilder();
	}

	function tearDown()
	{
		unset($this->builder);
	}

/**
* HTMLBuilder class tests
*/	
	function testHTMLBilderSetOutputPath()
	{
		$path = "test/path";
		$this->builder->html_builder->setOutputPath($path);
		$this->assertEquals($path, $this->builder->html_builder->output_path);
	}

	function testHTMLBuilderSetSkinPath()
	{
		$path = "test/skin_path";
		$this->builder->html_builder->setSkinPath($path);
		$this->assertEquals($path, $this->builder->html_builder->_skin_path);
	}

	function testHTMLBuilderSetTemplateEngine()
	{
		$engine = new TemplateEngine();
		$this->builder->html_builder->setTemplateEngine($engine);
		$this->assertEquals($engine, $this->builder->html_builder->_template_engine);
	}
	
	function testHTMLBuilderClear()
	{
		$test_path = "var/TEST_CLEAR/";
		$path = $this->builder->html_builder->output_path;
		$this->builder->html_builder->setOutputPath($test_path);
		@mkdir($test_path, 0777);
		
		$this->builder->html_builder->clear();
		$result = true;
		clearstatcache();
		if (is_dir($this->builder->html_builder->output_path)) {
			$dir = dir($this->builder->html_builder->output_path);
			while ($filename = $dir->read()) {
				if ($filename != '.' && $filename != '..') {
					$result = false;
					break;
				}
			}	
			$dir->close();
		}
		$this->assertTrue($result);

		$this->builder->html_builder->setOutputPath($path);
		rmdir("var/TEST_CLEAR/");
	}

	function testHTMLBuilderGetFailedFiles()
	{
		$failed_test = array("file1", "file2");
		$this->builder->html_builder->_failed_files = $failed_test;
		$result = $this->builder->html_builder->getFailedFiles();
		$this->assertEquals($result, $failed_test);
	}

	function testUpdateAnchors()
	{
		$result = $this->builder->html_builder->_anchor_checker->updateAnchors();
		$this->assertFalse(PEAR::isError($result));
	}

}

$suite = new PHPUnit_TestSuite("BuilderTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
