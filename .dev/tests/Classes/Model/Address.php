<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Address class tests
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

class XLite_Tests_Model_Address extends XLite_Tests_TestCase
{

    /**
     * addressFields 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $addressFields = array(
        'is_billing'   => 2,
        'is_shipping'  => 3,
        'address_type' => 'W',
        'title'        => 'title test',
        'firstname'    => 'firstname test',
        'lastname'     => 'lastname test',
        'street'       => 'street test',
        'city'         => 'city test',
        'state_id'     => 34,
        'custom_state' => 'custom_state test',
        'country_code' => 'US',
        'zipcode'      => 'zipcode test',
        'phone'        => 'phone test',
    );

    /**
     * testCreate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCreate()
    {
        $address = new \XLite\Model\Address();

        $this->assertNull($address->getAddressId(), 'address_id checking');

        foreach ($this->addressFields as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $address->$setterMethod($testValue);
            $value = $address->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking ('.$field.')');
        }

        $profile = new \XLite\Model\Profile();

        $address->setProfile($profile);

        $this->assertTrue($address->getProfile() instanceof \XLite\Model\Profile, 'Profile checking');
    }

    /**
     * testGetState 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetState()
    {
        $address = new \XLite\Model\Address();

        $address->map($this->addressFields);

        $this->assertTrue($address->getState() instanceof \XLite\Model\State, 'State checking');
        $this->assertEquals($address->getStateId(), $address->getState()->getStateId(), 'state id checking');
    }

    /**
     * testGetCountry 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetCountry()
    {
        $address = new \XLite\Model\Address();

        $address->map($this->addressFields);

        $this->assertTrue($address->getCountry() instanceof \XLite\Model\Country, 'Country checking');
        $this->assertEquals($address->getCountryCode(), $address->getCountry()->getCode(), 'country code checking');
    }

    /**
     * testGetAddressFields 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetAddressFields()
    {
        $result = \XLite\Model\Address::getAddressFields();

        $this->assertTrue(is_array($result), 'check that getAddressFields() returns an array');
        $this->assertTrue(!empty($result), 'check that getAddressFields() returns non empty array');
    }

}
