<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class WidgetTest extends PHPUnit_TestCase
{
    var $widget;

    function WidgetTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        $this->widget = func_new("Widget");
        $this->widget->set("name", "Name");
        @unlink("var/run/tests/classes/base/test_template.tpl.php");
        @unlink("var/run/tests/classes/base/test_template.tpl.init.php");
    }

    function tearDown()
    {
        unset($this->widget);
    }

	function testprice_format()
	{
		$this->widget->config->General->thousand_delim = '%';
		$this->widget->config->General->decimal_delim = '**';
		$this->widget->config->General->price_format = '%s rub.';
		$this->assertEquals("1%234**33 rub.", $this->widget->price_format(1234.33));
		$this->widget->config->General->decimal_delim = '';
		$this->assertEquals("1%234 rub.", $this->widget->price_format(1234.33));
	}

	function test_wrap()
	{
		$w = func_new("Widget");
		$this->assertEquals("asd..\nasdasd@\n@", $w->wrap("asd..asdasd@@", 0, 5));
		$this->assertEquals("a.asdasd@\nasdasd-asdasd", $w->wrap("a.asdasd@asdasd-asdasd", 0, 10));
		$this->assertEquals("aasdasdsdasd-asdasd", $w->wrap("aasdasdsdasd-asdasd", 0, 10));
	}

    function testDisplay()
    {
        $w = func_new("Widget");
        $w->set("templateFile", "tests/classes/base/test_template.tpl");
        $w->set("template", "base/test_template.tpl");
        $w->config->General->thousand_delim = '%';
        $w->config->General->decimal_delim = '**';
        $w->config->General->price_format = '%s rub.';
        $w->var = "asd";
        $w->price = 123123.12;
        ob_start();
        $w->display();
        $contents = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<img src="skins/default/en/images/image.gif">asd123%123**12 rub.', trim($contents));
    }

}

$suite = new PHPUnit_TestSuite("WidgetTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
