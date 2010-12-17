<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Zone class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Tests_Model_Zone extends XLite_Tests_TestCase
{
	/**
	 * testGetZoneWeight
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function testGetZoneWeight()
	{
        $data = array();
        $data[] = array(
            'zoneid' => 20,
            'address'  => array(
                'country' => 'US',
                'state'   => 'NY',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0x01 + 0x02 + 0x08 + 0x10,
        );

        $data[] = array(
            'zoneid' => 20,
            'address'  => array(
                'country' => 'US',
                'state'   => 'NY',
                'city'    => 'New Worker',
                'zipcode' => '10134',
                'address' => '92nd Street Y 1395 Lexington Avenue',
            ),
            'weight' => 0x01 + 0x02 + 0x08 + 0x20,
        );

        $data[] = array(
            'zoneid' => 20,
            'address'  => array(
                'country' => 'US',
                'state'   => 'CA',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0,
        );

        $data[] = array(
            'zoneid' => 20,
            'address'  => array(
                'country' => 'FR',
                'state'   => 'NY',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0,
        );

        $data[] = array(
            'zoneid' => 40,
            'address'  => array(
                'country' => 'FR',
                'state'   => 'NY',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0x01,
        );

        $data[] = array(
            'zoneid' => 50,
            'address'  => array(
                'country' => 'FR',
                'state'   => 'NY',
                'city'    => 'New York',
                'zipcode' => '10134',
                'address' => 'Some address',
            ),
            'weight' => 0,
        );

        foreach ($data as $i => $dt) {

            $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone($dt['zoneid']);
            
            if (20 == $zone->getZoneId()) {
                $zoneElement = new \XLite\Model\ZoneElement();
                $zoneElement->setElementValue('%Lexington%');
                $zoneElement->setElementType('A');
                $zoneElement->setZone($zone);

                $zone->addZoneElements($zoneElement);

                $flag = true;
            }

            $this->assertEquals($dt['weight'], $zone->getZoneWeight($dt['address']), 'check ' . $i . ' iteration');
        }
    }

    /**
     * testGetZoneCountries 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetZoneCountries()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);

        $includedCountries = $zone->getZoneCountries();
        $this->assertTrue(is_array($includedCountries), 'getZoneCountries() must return an array');

        $found = false;
        foreach ($includedCountries as $country) {

            $this->assertTrue($country instanceof \XLite\Model\Country, 'countries must be objects');

            if ('US' == $country->getCode()) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'US is not found in zone definition');

        $excludedCountries = $zone->getZoneCountries(true);
        $this->assertTrue(is_array($includedCountries), 'getZoneCountries() must return an array');

        $found = false;
        foreach ($excludedCountries as $country) {

            $this->assertTrue($country instanceof \XLite\Model\Country, 'countries must be objects');

            if ('US' == $country->getCode()) {
                $found = true;
                break;
            }
        }
        $this->assertFalse($found, 'US is found in the excluded countries list of the zone');
    }

    /**
     * testGetZoneStates 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetZoneStates()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);

        $includedStates = $zone->getZoneStates();
        $this->assertTrue(is_array($includedStates), 'getZoneStates() must return an array');

        $found = false;
        foreach ($includedStates as $state) {

            $this->assertTrue($state instanceof \XLite\Model\State, 'states must be objects');

            if ('US' == $state->getCountry()->getCode() && 'NY' == $state->getCode()) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'US_NY is not found in zone definition');

        $excludedStates = $zone->getZoneStates(true);
        $this->assertTrue(is_array($includedStates), 'getZoneStates() must return an array');

        $found = false;
        foreach ($excludedStates as $state) {

            $this->assertTrue($state instanceof \XLite\Model\State, 'states must be objects');

            if ('US' == $state->getCountry()->getCode() && 'NY' == $state->getCode()) {
                $found = true;
                break;
            }
        }
        $this->assertFalse($found, 'US_NY is found in the excluded states list of the zone');
    }

    /**
     * testGetZoneCities 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetZoneCities()
    {
         $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);
        
         $cities = $zone->getZoneCities();

         $this->assertTrue(in_array('New York', $cities), 'New York is not found in zone definition');
    }

    /**
     * testGetZoneZipCodes 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetZoneZipCodes()
    {
         $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);
        
         $zipcodes = $zone->getZoneZipCodes();

         $this->assertTrue(in_array('101%', $zipcodes), '101% is not found in zone definition');
    }

    /**
     * testGetZoneAddresses 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetZoneAddresses()
    {
         $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);

         $zoneElement = new \XLite\Model\ZoneElement();
         $zoneElement->setElementValue('addr');
         $zoneElement->setElementType('A');
         $zoneElement->setZone($zone);

         $zone->addZoneElements($zoneElement);

         $addresses = $zone->getZoneAddresses();

         $this->assertTrue(in_array('addr', $addresses), 'addr is not found in zone definition');
    }

    /**
     * testHasZoneElements 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testHasZoneElements()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);
        $this->assertTrue($zone->hasZoneElements(), 'zone #20 (New York zone) is empty');

        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(50);
        $this->assertFalse($zone->hasZoneElements(), 'zone #50 (Atlantida) is not empty');

        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(1);
        $this->assertFalse($zone->hasZoneElements(), 'zone #1 (Default zone) is not empty');

    }

    /**
     * testGetElementsByType 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetElementsByType()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);

        $elements = $zone->getElementsByType('S');

        $this->assertTrue(is_array($elements), 'Elements must be an array');
        foreach ($elements as $key => $value) {
            $this->assertTrue(is_string($value), 'Values of element list must be a strings');
        }
    }

    /**
     * testGetZoneId 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetZoneId()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);

        $this->assertNotNull($zone, 'Zone not found');
        $this->assertEquals(20, $zone->getZoneId(), 'Zone Id does not match');
    }

    /**
     * testGetZoneName 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetZoneName()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);

        $this->assertNotNull($zone, 'Zone not found');
        $this->assertEquals('New York area', $zone->getZoneName(), 'Zone name does not match');

        $zone->setZoneName('Updated New York area');
        $this->assertEquals('Updated New York area', $zone->getZoneName(), 'Zone name does not match');

    }

    /**
     * testIsDefault 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetIsDefault()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);
        $this->assertFalse($zone->getIsDefault(), 'Zone #20 must not to be a default');

        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(1);
        $this->assertTrue($zone->GetIsDefault(), 'Zone #1 must be a default');
    }

    /**
     * testSetIsDefault 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSetIsDefault()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(20);
        $zone->setIsDefault(1);
        $this->assertEquals(1, $zone->getIsDefault(), 'Zone #20 setIsDefault(1) does not work');

        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(1);
        $zone->setIsDefault(0);
        $this->assertEquals(0, $zone->GetIsDefault(), 'Zone #1 setIsDefault(0) does not work');
    }

}
