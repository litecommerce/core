<?php

require_once "tests/classes/config.php";
require_once "PHPUnit.php";

class UPSTest extends PHPUnit_TestCase
{
    function UPSTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
		$shipping = func_new("Shipping_ups");
		$shipping->set("class", "ups");
		$shipping->set("name", "UPS Test");
		$shipping->set("enabled", 1);
		$shipping->set("destination", "I");
		$shipping->create();
		$this->shipping =& $shipping;
    }

    function tearDown()
    {
		$this->shipping->delete();
    }
	
	function test_parseResponseSuccess()
	{
		$response =<<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<RatingServiceSelectionResponse>
    <Response>
        <TransactionReference>
            <CustomerContext>Rating and Service</CustomerContext>
            <XpciVersion>1.0001</XpciVersion>
        </TransactionReference>
        <ResponseStatusCode>1</ResponseStatusCode>
        <ResponseStatusDescription>Success</ResponseStatusDescription>
    </Response>
    <RatedShipment>
        <Service>
            <Code>03</Code>
        </Service>
        <BillingWeight>
            <UnitOfMeasurement>
                <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>1.0</Weight>
        </BillingWeight>
        <TransportationCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>5.43</MonetaryValue>
        </TransportationCharges>
        <ServiceOptionsCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>0.00</MonetaryValue>
        </ServiceOptionsCharges>
        <TotalCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>5.43</MonetaryValue>
        </TotalCharges>
        <GuaranteedDaysToDelivery/>
        <ScheduledDeliveryTime/>
        <RatedPackage>
            <TransportationCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>5.43</MonetaryValue>
            </TransportationCharges>
            <ServiceOptionsCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>0.00</MonetaryValue>
            </ServiceOptionsCharges>
            <TotalCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>5.43</MonetaryValue>
            </TotalCharges>
            <Weight>1.0</Weight>
            <BillingWeight>
                <UnitOfMeasurement>
                    <Code>LBS</Code>
                </UnitOfMeasurement>
                <Weight>1.0</Weight>
            </BillingWeight>
        </RatedPackage>
    </RatedShipment>
    <RatedShipment>
        <Service>
            <Code>12</Code>
        </Service>
        <BillingWeight>
            <UnitOfMeasurement>
                <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>1.0</Weight>
        </BillingWeight>
        <TransportationCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>7.41</MonetaryValue>
        </TransportationCharges>
        <ServiceOptionsCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>0.00</MonetaryValue>
        </ServiceOptionsCharges>
        <TotalCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>7.41</MonetaryValue>
        </TotalCharges>
        <GuaranteedDaysToDelivery>3</GuaranteedDaysToDelivery>
        <ScheduledDeliveryTime/>
        <RatedPackage>
            <TransportationCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>7.41</MonetaryValue>
            </TransportationCharges>
            <ServiceOptionsCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>0.00</MonetaryValue>
            </ServiceOptionsCharges>
            <TotalCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>7.41</MonetaryValue>
            </TotalCharges>
            <Weight>1.0</Weight>
            <BillingWeight>
                <UnitOfMeasurement>
                    <Code>LBS</Code>
                </UnitOfMeasurement>
                <Weight>1.0</Weight>
            </BillingWeight>
        </RatedPackage>
    </RatedShipment>
    <RatedShipment>
        <Service>
            <Code>59</Code>
        </Service>
        <BillingWeight>
            <UnitOfMeasurement>
                <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>1.0</Weight>
        </BillingWeight>
        <TransportationCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>11.06</MonetaryValue>
        </TransportationCharges>
        <ServiceOptionsCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>0.00</MonetaryValue>
        </ServiceOptionsCharges>
        <TotalCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>11.06</MonetaryValue>
        </TotalCharges>
        <GuaranteedDaysToDelivery>2</GuaranteedDaysToDelivery>
        <ScheduledDeliveryTime>12:00 Noon</ScheduledDeliveryTime>
        <RatedPackage>
            <TransportationCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>11.06</MonetaryValue>
            </TransportationCharges>
            <ServiceOptionsCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>0.00</MonetaryValue>
            </ServiceOptionsCharges>
            <TotalCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>11.06</MonetaryValue>
            </TotalCharges>
            <Weight>1.0</Weight>
            <BillingWeight>
                <UnitOfMeasurement>
                    <Code>LBS</Code>
                </UnitOfMeasurement>
                <Weight>1.0</Weight>
            </BillingWeight>
        </RatedPackage>
    </RatedShipment>
    <RatedShipment>
        <Service>
            <Code>02</Code>
        </Service>
        <BillingWeight>
            <UnitOfMeasurement>
                <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>1.0</Weight>
        </BillingWeight>
        <TransportationCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>9.85</MonetaryValue>
        </TransportationCharges>
        <ServiceOptionsCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>0.00</MonetaryValue>
        </ServiceOptionsCharges>
        <TotalCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>9.85</MonetaryValue>
        </TotalCharges>
        <GuaranteedDaysToDelivery>2</GuaranteedDaysToDelivery>
        <ScheduledDeliveryTime/>
        <RatedPackage>
            <TransportationCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>9.85</MonetaryValue>
            </TransportationCharges>
            <ServiceOptionsCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>0.00</MonetaryValue>
            </ServiceOptionsCharges>
            <TotalCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>9.85</MonetaryValue>
            </TotalCharges>
            <Weight>1.0</Weight>
            <BillingWeight>
                <UnitOfMeasurement>
                    <Code>LBS</Code>
                </UnitOfMeasurement>
                <Weight>1.0</Weight>
            </BillingWeight>
        </RatedPackage>
    </RatedShipment>
    <RatedShipment>
        <Service>
            <Code>13</Code>
        </Service>
        <BillingWeight>
            <UnitOfMeasurement>
                <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>1.0</Weight>
        </BillingWeight>
        <TransportationCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>23.35</MonetaryValue>
        </TransportationCharges>
        <ServiceOptionsCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>0.00</MonetaryValue>
        </ServiceOptionsCharges>
        <TotalCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>23.35</MonetaryValue>
        </TotalCharges>
        <GuaranteedDaysToDelivery>1</GuaranteedDaysToDelivery>
        <ScheduledDeliveryTime>3:00 P.M.</ScheduledDeliveryTime>
        <RatedPackage>
            <TransportationCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>23.35</MonetaryValue>
            </TransportationCharges>
            <ServiceOptionsCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>0.00</MonetaryValue>
            </ServiceOptionsCharges>
            <TotalCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>23.35</MonetaryValue>
            </TotalCharges>
            <Weight>1.0</Weight>
            <BillingWeight>
                <UnitOfMeasurement>
                    <Code>LBS</Code>
                </UnitOfMeasurement>
                <Weight>1.0</Weight>
            </BillingWeight>
        </RatedPackage>
    </RatedShipment>
    <RatedShipment>
        <Service>
            <Code>14</Code>
        </Service>
        <BillingWeight>
            <UnitOfMeasurement>
                <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>1.0</Weight>
        </BillingWeight>
        <TransportationCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>54.05</MonetaryValue>
        </TransportationCharges>
        <ServiceOptionsCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>0.00</MonetaryValue>
        </ServiceOptionsCharges>
        <TotalCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>54.05</MonetaryValue>
        </TotalCharges>
        <GuaranteedDaysToDelivery>1</GuaranteedDaysToDelivery>
        <ScheduledDeliveryTime>8:00 A.M.</ScheduledDeliveryTime>
        <RatedPackage>
            <TransportationCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>54.05</MonetaryValue>
            </TransportationCharges>
            <ServiceOptionsCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>0.00</MonetaryValue>
            </ServiceOptionsCharges>
            <TotalCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>54.05</MonetaryValue>
            </TotalCharges>
            <Weight>1.0</Weight>
            <BillingWeight>
                <UnitOfMeasurement>
                    <Code>LBS</Code>
                </UnitOfMeasurement>
                <Weight>1.0</Weight>
            </BillingWeight>
        </RatedPackage>
    </RatedShipment>
    <RatedShipment>
        <Service>
            <Code>01</Code>
        </Service>
        <BillingWeight>
            <UnitOfMeasurement>
                <Code>LBS</Code>
            </UnitOfMeasurement>
            <Weight>1.0</Weight>
        </BillingWeight>
        <TransportationCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>25.12</MonetaryValue>
        </TransportationCharges>
        <ServiceOptionsCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>0.00</MonetaryValue>
        </ServiceOptionsCharges>
        <TotalCharges>
            <CurrencyCode>USD</CurrencyCode>
            <MonetaryValue>25.12</MonetaryValue>
        </TotalCharges>
        <GuaranteedDaysToDelivery>1</GuaranteedDaysToDelivery>
        <ScheduledDeliveryTime>10:30 A.M.</ScheduledDeliveryTime>
        <RatedPackage>
            <TransportationCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>25.12</MonetaryValue>
            </TransportationCharges>
            <ServiceOptionsCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>0.00</MonetaryValue>
            </ServiceOptionsCharges>
            <TotalCharges>
                <CurrencyCode>USD</CurrencyCode>
                <MonetaryValue>25.12</MonetaryValue>
            </TotalCharges>
            <Weight>1.0</Weight>
            <BillingWeight>
                <UnitOfMeasurement>
                    <Code>LBS</Code>
                </UnitOfMeasurement>
                <Weight>1.0</Weight>
            </BillingWeight>
        </RatedPackage>
    </RatedShipment>
</RatingServiceSelectionResponse>
EOF;
		$result = $this->shipping->_parseResponse(trim($response), "L", "US");
		$this->assertEquals("", $this->shipping->error);
		$this->assertUPSResponse(array(
			"UPS Ground" => 5.43,
			"UPS 3 Day Select" => 7.41,
			"UPS 2nd Day Air A.M." => 11.06,
			"UPS 2nd Day Air" => 9.85,
			"UPS Next Day Air Saver" => 23.35,
			"UPS Next Day Air Early A.M." => 54.05,
			"UPS Next Day Air" => 25.12,
			), $result);
	}

	function assertUPSResponse($model, $result)
	{
		$methods = array();
		foreach ($this->shipping->findAll("class='ups'") as $s) {
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
		$result = $this->shipping->_parseResponse("", "I", "US");
		$this->assertTrue($this->shipping->error);
		$this->assertTrue($this->shipping->xmlError);
	}
	
	function test_parseErrorResponse()
	{
		$response = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<RatingServiceSelectionResponse>
    <Response>
        <TransactionReference/>
        <ResponseStatusCode>0</ResponseStatusCode>
        <ResponseStatusDescription>Failure</ResponseStatusDescription>
        <Error>
            <ErrorSeverity>Hard</ErrorSeverity>
            <ErrorCode>250005</ErrorCode>
            <ErrorDescription>No Access Identification provided</ErrorDescription>
        </Error>
    </Response>
</RatingServiceSelectionResponse>
EOT;
		$result = $this->shipping->_parseResponse($response, "L", "US");
		$this->assertFalse($result);
		$this->assertEquals("UPS error #250005: No Access Identification provided", $this->shipping->error);
	}
	
/*	function test_queryRates()
	{
		$options = $this->shipping->getOptions();
		$this->shipping->_queryRates(1, "73003", "US", "10001", "US", $options);
	}*/
	
	function test_getRates_Cache()
	{
	 	$shipping = func_new("myups");
		$shipping->cleanCache();
		$shipping->shipping =& $this->shipping;
		$shipping->shippingRate = 111.11;
		$order = func_new("myOrder");
		$order->profile = func_new("Profile");
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
		$order->set("profile.shipping_zipcode", "123456");
		$rates = $shipping->getRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate);
		}
		$shipping->shippingRate = 333.33;
		// change options
		$oldContents = $shipping->packaging;
		$shipping->packaging = "01";
		$rates = $shipping->getRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(333.33, $rate->rate);
		}
		// must remember previous value
		$order->profile->set("shipping_zipcode", "");
		$shipping->packaging = $oldContents;
		$rates = $shipping->getRates($order);
		$this->assertEquals(1, count($rates));
		foreach ($rates as $rate) {
			$this->assertEquals(111.11, $rate->rate);
		}
		// clean cache
		$shipping->cleanCache();

	}

}

func_new("Shipping_ups");
class myups extends Shipping_ups__ {
	var $shippingRate;
	var $packaging = "00";
	var $shipping;

	function _queryRates($response, $destination, $originCountry)
	{
		$rate = new StdClass;
		$rate->rate = $this->shippingRate;
		$rate->shipping = $this->shipping;
		return array($rate);
	}
	
	function &getOptions()
	{
		$options = parent::getOptions();
		$options->packaging = $this->packaging;
		return $options;
	}
	
}
func_new("Order");
class myOrder extends Order__{
	function getWeight(){ return 1; }
}

$suite = new PHPUnit_TestSuite("UPSTest");
$result = PHPUnit::run($suite);

include "tests/classes/result.php";

?>
