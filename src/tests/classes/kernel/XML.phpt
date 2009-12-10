<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";
require_once "kernel/XML.php";

class XML_test extends PHPUnit_TestCase
{
    function XML_test($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$this->xml = new XML;
    }

    function tearDown()
    {
    }

	function test_compileTree()
	{
		$xml =<<<EOF
<?xml version="1.0"?>
<shipment xsi:schemaLocation="http://www.intershipper.com/Interface/Intershipper/XML/v2.0/schemas response.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><version>2.0.0.0</version>
	<package id="1"><boxID>box1</boxID>
		<quote id="1"><carrier><code>UPS</code><name>United Parcel Service</name></carrier><class><code>GND</code><name>Ground</name></class></quote>
		<quote id="2"><carrier><code>UPS</code><name>United Parcel Service</name></carrier></quote>
	</package>
</shipment>	
EOF;
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $xml, $values, $index);
		$i=0;
		$this->assertEquals('a:1:{s:8:"SHIPMENT";a:2:{s:7:"VERSION";s:7:"2.0.0.0";s:7:"PACKAGE";a:1:{i:1;a:2:{s:5:"BOXID";s:4:"box1";s:5:"QUOTE";a:2:{i:1;a:2:{s:7:"CARRIER";a:2:{s:4:"CODE";s:3:"UPS";s:4:"NAME";s:21:"United Parcel Service";}s:5:"CLASS";a:2:{s:4:"CODE";s:3:"GND";s:4:"NAME";s:6:"Ground";}}i:2;a:1:{s:7:"CARRIER";a:2:{s:4:"CODE";s:3:"UPS";s:4:"NAME";s:21:"United Parcel Service";}}}}}}}', serialize($this->xml->_compileTree($values, $i)));
	}

	function testParse()
	{
		$this->assertEquals('a:1:{s:3:"TAG";a:2:{s:6:"NESTED";s:5:"value";s:4:"SERI";a:2:{i:1;s:5:"first";i:2;s:6:"second";}}}', serialize($this->xml->parse("<tag><nested>value</nested><seri id=\"1\">first</seri><seri id=\"2\">second</seri></tag>")));
	}

	function testParse_repeating_tags()
	{
		$this->assertEquals('a:1:{s:3:"TAG";a:3:{s:6:"NESTED";s:5:"value";s:4:"SERI";s:5:"first";s:5:"SERI1";s:6:"second";}}', serialize($this->xml->parse("<tag><nested>value</nested><seri>first</seri><seri>second</seri></tag>")));
	}


	function testParseError()
	{
		$this->assertFalse($this->xml->parse(""));
		$this->assertTrue($this->xml->error);
	}
}


$suite = new PHPUnit_TestSuite("XML_test");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
