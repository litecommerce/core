<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class USPSTest extends PHPUnit_TestCase
{
    function USPSTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        global $xlite;
	    $conn = $xlite->db;
		$conn->query("delete from xlite_shipping where class='usps'");

		$this->GXGshipping = func_new("Shipping");
		$this->GXGshipping->set("class", "usps");
		$this->GXGshipping->set("name", "U.S.P.S. Global Express Guaranteed (GXG) Non-Document Service");
		$this->GXGshipping->set("enabled", 1);
		$this->GXGshipping->set("destination", "I");
		$this->GXGshipping->create();

		$shipping = func_new("Shipping_usps");
		$this->options = $shipping->getOptions();
		$this->options->server = "http://testing.shippingapis.com/ShippingAPITest.dll";
		$this->options->userid = "751RRFRU3051";
		$this->options->password = "397FM75VV605";
    }

    function tearDown()
    {
        global $xlite;
	    $conn = $xlite->db;
		$conn->query("delete from xlite_shipping where class='usps'");
    }
	
	
	function test_parseResponseError()
	{
	 	$response =<<<EOT
<?xml version="1.0" ?>
<IntlRateResponse>
<Package ID="0">
<Error>
<Number>-2147218803</Number>
<Source>SOLServerTest;SOLServerTest.CallIntlRateDll</Source>
<Description>Please enter a valid weight for pounds.</Description>
<HelpFile />
<HelpContext>1000440</HelpContext>
</Error>
</Package>
</IntlRateResponse>
EOT;
		$shipping = func_new("Shipping_usps");
		$result = $shipping->_parseResponse(trim($response), "I");
		$this->assertEquals("Please enter a valid weight for pounds.", $shipping->error);
		$this->assertFalse($result);
	}

	function test_parseResponseError1()
	{
	 	$response =<<<EOT
<?xml version="1.0"?>
<RateResponse>
<Package ID="0">
	<Service>EXPRESS</Service>
	<ZipOrigination>20770</ZipOrigination>
	<ZipDestination>54324</ZipDestination>
	<Pounds>2</Pounds>
	<Ounces>0</Ounces>
	<Container>NONE</Container>
	<Size>REGULAR</Size>
	<Zone>5</Zone>
	<Postage>16.00</Postage>
</Package>
<Package ID="1">
	<Service>PRIORITY</Service>
	<ZipOrigination>20770</ZipOrigination>
	<ZipDestination>02912</ZipDestination>
	<Pounds>20</Pounds>
	<Ounces>8</Ounces>
	<Container>NONE</Container>
	<Size>REGULAR</Size>
	<Zone>4</Zone>
	<Postage>16.35</Postage>
</Package>
<Package ID="2">
	<Service>PARCEL</Service>
	<Error>
		<Number>-2147218803</Number>
		<Source>SOLServerTest;SOLServerTest.CallIntlRateDll</Source>
		<Description>Please enter a valid weight for pounds.</Description>
		<HelpFile />
		<HelpContext>1000440</HelpContext>
	</Error>
</Package></RateResponse>
EOT;
		$shipping = func_new("Shipping_usps");
		$result = $shipping->_parseResponse(trim($response), "L");
		$this->assertEquals("Please enter a valid weight for pounds.", $shipping->error);
		$this->assertTrue($result);
	}

	function test_parseResponseSuccess()
	{
		$shipping = func_new("Shipping_usps");
		$result = $shipping->_parseResponse(trim($this->validResponse1()), "I");
		$this->assertEquals("", $shipping->error);
		$this->assertUSPSResponse(array(
			"U.S.P.S. Global Express Guaranteed (GXG) Document Service" => 87.0,
			"U.S.P.S. Global Express Guaranteed (GXG) Non-Document Service" => 96.0), $result);
	}

	function test_parseDomesticResponseSuccess()
	{
		$shipping = func_new("Shipping_usps");
		$response =<<<EOT
<?xml version="1.0"?>
<RateResponse>
<Package ID="0">
	<Service>EXPRESS</Service>
	<ZipOrigination>20770</ZipOrigination>
	<ZipDestination>54324</ZipDestination>
	<Pounds>2</Pounds>
	<Ounces>0</Ounces>
	<Container>NONE</Container>
	<Size>REGULAR</Size>
	<Zone>5</Zone>
	<Postage>16.00</Postage>
</Package>
<Package ID="1">
	<Service>PRIORITY</Service>
	<ZipOrigination>20770</ZipOrigination>
	<ZipDestination>02912</ZipDestination>
	<Pounds>20</Pounds>
	<Ounces>8</Ounces>
	<Container>NONE</Container>
	<Size>REGULAR</Size>
	<Zone>4</Zone>
	<Postage>16.35</Postage>
</Package>
<Package ID="2">
	<Service>PARCEL</Service>
	<ZipOrigination>20770</ZipOrigination>
	<ZipDestination>02912</ZipDestination>
	<Pounds>20</Pounds>
	<Ounces>8</Ounces>
	<Container>NONE</Container>
	<Size>REGULAR</Size>
	<Machinable>TRUE</Machinable>
	<Zone>4</Zone>
	<Postage>9.93</Postage>
</Package></RateResponse>
EOT;
		$result = $shipping->_parseResponse(trim($response), "L");
		$this->assertEquals("", $shipping->error);
		if ($shipping->xmlError) {
//			print $shipping->response;
		}
		$this->assertUSPSResponse(array(
			"U.S.P.S. Express Mail" => 16.00,
			"U.S.P.S. Priority Mail" => 16.35,
			"U.S.P.S. Parcel Post" => 9.93),
			$result);
	}

	function assertUSPSResponse($model, $result)
	{
		$shipping = func_new("Shipping");
		$methods = array();
		foreach ($shipping->findAll("class='usps'") as $s) {
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
		$shipping = func_new("Shipping_usps");
		$result = $shipping->_parseResponse("", "I");
		$this->assertTrue($shipping->error);
	}

/*	function testValidRequest1()
	{
		
		$shipping = func_new("Shipping_usps",);
		$result = $shipping->_request($this->validRequest1(), $this->options);
		$result = $shipping->_parseResponse($result, "I");
	    $this->assertUSPSResponse(array(
			"U.S.P.S. Global Express Guaranteed (GXG) Document Service" => 87.0,
            "U.S.P.S. Global Express Guaranteed (GXG) Non-Document Service" => 96.0), $result);
	}

	function testValidRequest2()
	{
		$shipping = func_new("Shipping_usps",);
		$result = $shipping->_request($this->validRequest2(), $this->options);
		$result = $shipping->_parseResponse($result, "I");
	    $this->assertUSPSResponse(array(
			"U.S.P.S. Postcards-Airmail" => 0.7,
            "U.S.P.S. Aerogrammes - Airmail" => 0.60), $result);
	}

	function test_Invalid_Weight_for_Pounds()
	{
		$shipping = func_new("Shipping_usps",);
		$result = $shipping->_request($this->errorRequest1(), $this->options);
		$result = $shipping->_parseResponse($result, "I");
	    $this->assertUSPSResponse(array(), $result);
		$this->assertEquals("Please enter a valid weight for pounds.", $shipping->error);
	}
	function test_Invalid_Weight_for_Ounces()
	{
		$shipping = func_new("Shipping_usps",);
		$result = $shipping->_request($this->errorRequest2(), $this->options);
		$result = $shipping->_parseResponse($result, "I");
	    $this->assertUSPSResponse(array(), $result);
		$this->assertEquals("Please enter a valid weight for ounces.", $shipping->error);
	}
	function test_No_Weight_Entered()
	{
		$shipping = func_new("Shipping_usps",);
		$result = $shipping->_request($this->errorRequest3(), $this->options);
		$result = $shipping->_parseResponse($result, "I");
	    $this->assertUSPSResponse(array(), $result);
		$this->assertEquals("Please enter the package weight.", $shipping->error);
	}
	function test_Invalid_Mail_Type()
	{
		$shipping = func_new("Shipping_usps",);
		$result = $shipping->_request($this->errorRequest4(), $this->options);
		$result = $shipping->_parseResponse($result, "I");
	    $this->assertUSPSResponse(array(), $result);
		$this->assertEquals("Invalid International Mail Type.", $shipping->error);
	}
	function test_Invalid_Country()
	{
		$shipping = func_new("Shipping_usps",);
		$result = $shipping->_request($this->errorRequest5(), $this->options);
		$result = $shipping->_parseResponse($result, "I");
	    $this->assertUSPSResponse(array(), $result);
		$this->assertEquals("Invalid Country.", $shipping->error);
	}*/
	function getOptions()
	{
		return $this->options;
	}
	
	function validRequest1()
	{
		$options = $this->getOptions();
		$result = "API=IntlRate&XML=".urlencode("<IntlRateRequest USERID=\"$options->userid\" PASSWORD=\"$options->password\"><Package ID=\"0\"><Pounds>2</Pounds><Ounces>0</Ounces><MailType>Package</MailType><Country>Albania</Country></Package></IntlRateRequest>");
		return $result;
	}	

	function validRequest2()
	{
		$options = $this->getOptions();
		return "API=IntlRate&XML=".urlencode("<IntlRateRequest USERID=\"$options->userid\" PASSWORD=\"$options->password\"><Package ID=\"0\"><Pounds>0</Pounds><Ounces>1</Ounces><MailType>Postcards or Aerogrammes</MailType><Country>Algeria</Country></Package></IntlRateRequest>");
	}
	function errorRequest1()
	{
		$options = $this->getOptions();
		return "API=IntlRate&XML=".urlencode("<IntlRateRequest USERID=\"$options->userid\" PASSWORD=\"$options->password\"><Package ID=\"0\"><Pounds>two</Pounds><Ounces>0</Ounces><MailType>Package</MailType><Country>Albania</Country></Package></IntlRateRequest>");
	}
	function errorRequest2()
	{
		$options = $this->getOptions();
		return "API=IntlRate&XML=".urlencode("<IntlRateRequest USERID=\"$options->userid\" PASSWORD=\"$options->password\"><Package ID=\"0\"><Pounds>2</Pounds><Ounces>zero</Ounces><MailType>Package</MailType><Country>Albania</Country></Package></IntlRateRequest>");
	}
	function errorRequest3()
	{
		$options = $this->getOptions();
		return "API=IntlRate&XML=".urlencode("<IntlRateRequest USERID=\"$options->userid\" PASSWORD=\"$options->password\"><Package ID=\"0\"><Pounds>0</Pounds><Ounces>0</Ounces><MailType>Package</MailType><Country>Albania</Country></Package></IntlRateRequest>");
	}
	function errorRequest4()
	{
		$options = $this->getOptions();
		return "API=IntlRate&XML=".urlencode("<IntlRateRequest USERID=\"$options->userid\" PASSWORD=\"$options->password\"><Package ID=\"0\"><Pounds>2</Pounds><Ounces>2</Ounces><MailType>Express</MailType><Country>Albania</Country></Package></IntlRateRequest>");
	}
	function errorRequest5()
	{
		$options = $this->getOptions();
		return "API=IntlRate&XML=".urlencode("<IntlRateRequest USERID=\"$options->userid\" PASSWORD=\"$options->password\"><Package ID=\"0\"><Pounds>2</Pounds><Ounces>2</Ounces><MailType>Package</MailType><Country>Alabama</Country></Package></IntlRateRequest>");
	}

	function validResponse1()
	{
// Response to Valid Test Request #1
	$response =<<<EOT
	<?xml version="1.0" ?>
	<IntlRateResponse>
	<Package ID="0">
	<Prohibitions>Currency of the Albanian State Bank (Banknotes in
	lek). Extravagant clothes and other articles contrary to
	Albanians' taste. Items sent by political emigres.</Prohibitions>
	<Restrictions>Hunting arms require an import permit. Medicines
	for personal use are admitted provided the addressee has a
	medical certificate.</Restrictions>
	<Observations>1. Letter packages may not contain dutiable
	articles. 2. Parcel post service extends only to: Berat Konispol
	Milot Bilisht Korce Peqin</Observations>
	<CustomsForms>Postal Union Mail (LC/AO): PS Form 2976 or 2976-A
	(see 123.61) Parcel Post: PS Form 2976-A inside 2976-E
	(envelope)</CustomsForms>
	<ExpressMail>Country Code AL Reciprocal Service Name EMS Required
	Customs Form/Endorsement 1. For correspondence and business
	papers: PS Form 2976, Customs - CN 22 (Old C 1) and Sender's
	Declaration (green label). Endorse item clearly next to mailing
	label as BUSINESS PAPERS.</ExpressMail>
	<AreasServed>Tirana.</AreasServed>
	<Service ID="0">
	<Pounds>2</Pounds>
	<Ounces>0</Ounces>
	<MailType>Package</MailType>
	<Country>ALBANIA</Country>
	<Postage>87</Postage>
	<SvcCommitments>See Service Guide</SvcCommitments>
	<SvcDescription>Global Express Guaranteed (GXG) Document
	Service</SvcDescription>
	<MaxDimensions>Max. length 46", depth 35", height 46" and max.
	girth 108"</MaxDimensions>
	<MaxWeight>22</MaxWeight>
	</Service>
	<Service ID="1">
	<Pounds>2</Pounds>
	<Ounces>0</Ounces>
	<MailType>Package</MailType>
	<Country>ALBANIA</Country>
	<Postage>96</Postage>
	<SvcCommitments>See Service Guide</SvcCommitments>
	<SvcDescription>Global Express Guaranteed (GXG) Non-Document
	Service</SvcDescription>
	<MaxDimensions>Max. length 46", depth 35", height 46" and max.
	girth 108"</MaxDimensions>
	<MaxWeight>22</MaxWeight>
	</Service>
	</Package>
	</IntlRateResponse>
EOT;
	return $response;
	}

	function test_InternationalRates_Cache()
	{
	 	$shipping = func_new("myusps");
		$shipping->cleanCache();
		$shipping->shippingRate = 111.11;
		$shipping->shipping = $this->GXGshipping;
		$order = func_new("Order");
		$order->_profile = func_new("Profile");
		$rates = $shipping->_getInternationalRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate);
		}
		$shipping->shippingRate = 222.22;
		$rates = $shipping->_getInternationalRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate); // must be cached
		}
		// change shipping country
		$order->get("profile.shipping_country", "RU");
		$rates = $shipping->_getInternationalRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate);
		}

	}

	function test_NationalRates_Cache()
	{
	 	$shipping = func_new("myusps");
		$shipping->cleanCache();
		$shipping->shippingRate = 111.11;
		$shipping->shipping = $this->GXGshipping;
		$order = func_new("Order");
		$order->profile = func_new("Profile");
		$rates = $shipping->_getNationalRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate);
		}
		$shipping->shippingRate = 222.22;
		$rates = $shipping->_getNationalRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate); // must be cached
		}
		// change shipping zip code
		$order->profile->set("shipping_zipcode", "123456");
		$rates = $shipping->_getNationalRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(222.22, $rate->rate);
		}
	}

}
func_new("Shipping_usps");
class myusps extends Shipping_usps__ {
	var $shippingRate;
	var $shipping;

	function _request($request, $options)
	{
		return '';
	}	

	function _parseResponse($response, $destination)
	{
		$rate = func_new("ShippingRate");
		$rate->rate = $this->shippingRate;
		$rate->shipping = $this->shipping;
		return array($rate);
	}
	
}

$suite = new PHPUnit_TestSuite("USPSTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
