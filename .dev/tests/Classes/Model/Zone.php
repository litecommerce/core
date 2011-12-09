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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Tests_Model_Zone extends XLite_Tests_TestCase
{
    static function setUpBeforeClass(){
        parent::setUpBeforeClass();
        xlite_restore_sql_from_backup();
    }
    protected $entityData = array(
        'zone_name'  => 'test name',
        'is_default' => true,
    );

    public function testCreate()
    {
        $c = new \XLite\Model\Zone();

        foreach ($this->entityData as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $c->$setterMethod($testValue);
            $value = $c->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking (' . $field . ')');
        }

        \XLite\Core\Database::getEM()->persist($c);
        \XLite\Core\Database::getEM()->flush();

        $this->assertTrue(0 < $c->getZoneId(), 'check zone id');


        \XLite\Core\Database::getEM()->remove($c);
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * testGetZoneWeight
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetZoneWeight()
    {
        $data = array();
        $data[] = array(
            'zone' => 'New York area',
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
            'zone' => 'New York area',
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
            'zone' => 'New York area',
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
            'zone' => 'New York area',
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
            'zone' => 'Europe',
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
            'zone' => 'Atlantida',
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

            $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => $dt['zone']));


            $this->assertNotNull($zone, 'check zone ' . $dt['zone']);

            if ('New York area' == $dt['zone']) {
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
     * @since  1.0.0
     */
    public function testGetZoneCountries()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

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
     * @since  1.0.0
     */
    public function testGetZoneStates()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

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
     * @since  1.0.0
     */
    public function testGetZoneCities()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

        $cities = $zone->getZoneCities();

        $this->assertTrue(in_array('New York', $cities), 'New York is not found in zone definition');
    }

    /**
     * testGetZoneZipCodes
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetZoneZipCodes()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

        $zipcodes = $zone->getZoneZipCodes();

        $this->assertTrue(in_array('101%', $zipcodes), '101% is not found in zone definition');
    }

    /**
     * testGetZoneAddresses
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetZoneAddresses()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

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
     * @since  1.0.0
     */
    public function testHasZoneElements()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

        $this->assertTrue($zone->hasZoneElements(), 'zone #20 (New York zone) is empty');

        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'Atlantida'));
        $this->assertNotNull($zone, 'check zone');
        $this->assertFalse($zone->hasZoneElements(), 'zone #50 (Atlantida) is not empty');

        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(1);
        $this->assertNotNull($zone, 'check zone');
        $this->assertFalse($zone->hasZoneElements(), 'zone #1 (Default zone) is not empty');
    }

    public function testgetZoneElements()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));
        $this->assertNotNull($zone, 'check zone');
        foreach ($zone->getZoneElements() as $e) {
            $this->assertTrue(0 < $e->getElementId(), 'check element id');
            $this->assertEquals($zone, $e->getZone(), 'check zone owner');
        }
    }

    /**
     * testGetElementsByType
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetElementsByType()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

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
     * @since  1.0.0
     */
    public function testGetZoneId()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

        $this->assertNotNull($zone, 'Zone not found');
        $this->assertTrue(0 < $zone->getZoneId(), 'Zone Id does not match');
    }

    /**
     * testGetZoneName
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetZoneName()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

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
     * @since  1.0.0
     */
    public function testGetIsDefault()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

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
     * @since  1.0.0
     */
    public function testSetIsDefault()
    {
        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findOneBy(array('zone_name' => 'New York area'));

        $this->assertNotNull($zone, 'check zone');

        $zone->setIsDefault(1);
        $this->assertEquals(1, $zone->getIsDefault(), 'Zone #20 setIsDefault(1) does not work');

        $zone = \XLite\Core\Database::getRepo('XLite\Model\Zone')->findZone(1);
        $zone->setIsDefault(0);
        $this->assertEquals(0, $zone->GetIsDefault(), 'Zone #1 setIsDefault(0) does not work');
    }

}
