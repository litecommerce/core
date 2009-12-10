<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class IntershipperTest extends PHPUnit_TestCase
{
    function IntershipperTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$shipping = func_new("Shipping_intershipper");;
		$shipping->set("class", "intershipper");
		$shipping->set("name", "U.S.P.S. Global Express Guaranteed (GXG) Non-Document Service");
		$shipping->set("enabled", 1);
		$shipping->set("destination", "I");
		$shipping->create();
		$this->shipping =& $shipping;
    }

    function tearDown()
    {
		$this->shipping->delete();
    }
	
	function test_parseResponseError()
	{
	 	$response =<<<EOT
<?xml version="1.0"?><shipment xsi:schemaLocation="http://www.intershipper.com/Interface/Intershipper/XML/v2.0/schemas response.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><error>Error Invalid Country Code ''</error></shipment>
EOT;
		$result = $this->shipping->_parseResponse(trim($response), "I");
		$this->assertEquals("Error Invalid Country Code ''", $this->shipping->error);
	}
	
	function test_parseResponseSuccess()
	{
		$response =<<<EOF
<?xml version="1.0"?>
<shipment xsi:schemaLocation="http://www.intershipper.com/Interface/Intershipper/XML/v2.0/schemas response.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<version>2.0.0.0</version>
<shipmentID>1234</shipmentID>
<queryID>YourQueryCode</queryID>
<deliveryType>COM</deliveryType>
<shipMethod>SCD</shipMethod>
<origination>
	<name>John Smith</name>
	<address1>123 Maple Lane</address1>
	<address2></address2>
	<address3></address3>
	<city>Phoenix</city>
	<state>AZ</state>
	<postal>85345</postal>
	<country>US</country>
</origination>
<destination>
	<name>Jane Doe</name>
	<address1>789 Oak Drive</address1>
	<address2>Suite #410</address2>
	<address3>Attention: Sales Department</address3>
	<city>Tempe</city>
	<state>AZ</state>
	<postal>85282</postal>
	<country>US</country>
</destination>
<package id="1">
	<boxID>YourBoxCode</boxID>
	<weight unit="LB">5</weight>
	<dimensions unit="IN">
		<length>10</length>
		<width>18</width>
		<height>20</height>
	</dimensions>
	<packaging>BOX</packaging>
	<contents>OTR</contents>
	<cod>0</cod>
	<insurance>0</insurance>
	<options>
		<specialHandling id="1">
			<code>SDP</code>
			<name>Saturday Pickup</name>
		</specialHandling>
	</options>
	<quote id="1">
		<carrier>
			<code>USP</code>
			<name>United States Postal Service</name>
			<account>123456789</account>
			<invoiced>0</invoiced>
		</carrier>
		<class>
			<code>GND</code>
			<name>Ground</name>
		</class>
		<service>
			<code>UGN</code>
			<name>USPS Ground (Non-Machinable)</name>
		</service>
		<rate>
			<amount>672</amount>
			<currency>USD</currency>
		</rate>
	</quote>
</package>
</shipment>
EOF;
		$result = $this->shipping->_parseResponse(trim($response), "L");
		$this->assertEquals("", $this->shipping->error);
		$this->assertIntershipperResponse(array(
			"USPS Ground (Non-Machinable)" => 6.72), $result);
	}

	function assertIntershipperResponse($model, $result)
	{
		$methods = array();
		foreach ($this->shipping->findAll("class='intershipper'") as $s) {
			if (array_key_exists($s->get("name"), $model)) {
				$methods[$s->get("shipping_id")] = $model[$s->get("name")];
			}
		}
		foreach ($result as $id => $rate) {
			$result[$id] = $rate->rate;
		}
		$this->assertEquals(count($methods), count($result));
		$this->assertEquals($methods, $result);
	}

	function test_parseResponseXMLError()
	{
		$result = $this->shipping->_parseResponse("", "I");
		$this->assertTrue($this->shipping->error);
		$this->assertTrue($this->shipping->xmlError);
	}

	function test_getRates_Cache()
	{
	 	$shipping = func_new("myintershipper");
		$shipping->cleanCache();
		$shipping->shipping =& $this->shipping;
		$shipping->shippingRate = 111.11;
		$order = func_new("Order");;
		$order->_profile = func_new("Profile");
		$rates = $shipping->getRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate);
		}
		$shipping->shippingRate = 222.22;
		$rates = $shipping->getRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate); // must be cached
		}
		// change shipping zip code
		$order->_profile->set("shipping_zipcode", "123456");
		$rates = $shipping->getRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(222.22, $rate->rate);
		}
		$shipping->shippingRate = 333.33;
		// change options
		$oldContents = $shipping->contents;
		$shipping->contents = "AHM";
		$rates = $shipping->getRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(333.33, $rate->rate);
		}
		// must remember previous value
		$order->_profile->set("shipping_zipcode", "");
		$shipping->contents = $oldContents;
		$rates = $shipping->getRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate);
		}
		// clean cache
		$shipping->cleanCache();

	}

}
func_new("Shipping_intershipper");
class myintershipper extends Shipping_intershipper__ {
	var $shippingRate;
	var $contents = "OTR";
	var $shipping;

	function _queryRates($weight, $ZipOrigination, $CountryOrigination,
	                         $ZipDestination, $CountryDestination,$options, $cod)
	{
		return $this->_parseResponse('', '');
	}	

	function _parseResponse($response, $destination)
	{
		$rate = func_new("StdClass");;
		$rate->rate = $this->shippingRate;
		$rate->shipping = $this->shipping;
		return array($rate);
	}
	
	function getOptions()
	{
		$options = parent::getOptions();
		$options->contents = $this->contents;
		return $options;
	}
	
}

$suite = new PHPUnit_TestSuite("IntershipperTest");
$result = PHPUnit::run($suite);

?>
