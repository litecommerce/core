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

    public function testName()
    {
        $address = new \XLite\Model\Address();

        $address->setName('first');

        $this->assertEquals('first', $address->getName(), 'check name #1');
        $this->assertEquals('first', $address->getFirstName(), 'check firstname #1');
        $this->assertEquals('', $address->getLastName(), 'check lastname #1');

        $address->setName('first2 last');

        $this->assertEquals('first2 last', $address->getName(), 'check name #2');
        $this->assertEquals('first2', $address->getFirstName(), 'check firstname #2');
        $this->assertEquals('last', $address->getLastName(), 'check lastname #2');

        $address->setName('first3 last2 mid');

        $this->assertEquals('first3 last2 mid', $address->getName(), 'check name #3');
        $this->assertEquals('first3', $address->getFirstName(), 'check firstname #3');
        $this->assertEquals('last2 mid', $address->getLastName(), 'check lastname #3');

        $address->setName('    first4      last3     mid   ');

        $this->assertEquals('first4 last3     mid', $address->getName(), 'check name #4');
        $this->assertEquals('first4', $address->getFirstName(), 'check firstname #4');
        $this->assertEquals('last3     mid', $address->getLastName(), 'check lastname #4');
    }

    public function testGetBillingRequiredFields()
    {
        $address = new \XLite\Model\Address();

        $this->assertEquals(
            array(
                'name',
                'street',
                'city',
                'zipcode',
                'state',
                'country',
            ),
            $address->getBillingRequiredFields(),
            'check equals'
        );
    }

    public function testGetShippingRequiredFields()
    {
        $address = new \XLite\Model\Address();

        $this->assertEquals(
            array(
                'name',
                'street',
                'city',
                'zipcode',
                'state',
                'country',
            ),
            $address->getShippingRequiredFields(),
            'check equals'
        );
    }

    public function testGetRequiredFieldsByType()
    {
        $address = new \XLite\Model\Address();

        $this->assertEquals(
            $address->getBillingRequiredFields(),
            $address->getRequiredFieldsByType($address::BILLING),
            'check billing'
        );

        $this->assertEquals(
            $address->getShippingRequiredFields(),
            $address->getRequiredFieldsByType($address::SHIPPING),
            'check shipping'
        );

        $this->assertNull(
            $address->getRequiredFieldsByType('z'),
            'check empty'
        );
    }

    public function testGetRequiredEmptyFields()
    {
        $address = new \XLite\Model\Address();
        $address->map($this->addressFields);
        $address->setCountry(\XLite\Core\Database::getRepo('XLite\Model\Country')->find('US'));

        $this->assertEquals(array(), $address->getRequiredEmptyFields($address::SHIPPING), 'check filled address');

        $address->setName('');
        $this->assertEquals(array('name'), $address->getRequiredEmptyFields($address::SHIPPING), 'check empty name');
    }

    public function testIsCompleted()
    {
        $address = new \XLite\Model\Address();
        $address->map($this->addressFields);
        $address->setCountry(\XLite\Core\Database::getRepo('XLite\Model\Country')->find('US'));

        $this->assertTrue($address->isCompleted($address::SHIPPING), 'check filled address');

        $address->setName('');
        $this->assertFalse($address->isCompleted($address::SHIPPING), 'check NOT filled address');

    }

    public function testCloneEntity()
    {
        $address = new \XLite\Model\Address();
        $address->map($this->addressFields);
        $address->setCountry(\XLite\Core\Database::getRepo('XLite\Model\Country')->find('US'));
        $address->setState(\XLite\Core\Database::getRepo('XLite\Model\State')->findOneByCountryAndCode('US', 'NY'));

        $address2 = $address->cloneEntity();

        foreach ($this->addressFields as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $value = $address2->$getterMethod();
            $this->assertEquals($testValue, $value, 'Cloning checking ('.$field.')');
        }

         $this->assertEquals($address->getCountry()->getCode(), $address2->getCountry()->getCode(), 'check country');
         $this->assertEquals($address->getState()->getStateId(), $address2->getState()->getStateId(), 'check state');
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

        $address->setCountry(\XLite\Core\Database::getRepo('XLite\Model\Country')->find('US'));
        $address->setState(\XLite\Core\Database::getRepo('XLite\Model\State')->findOneByCountryAndCode('US', 'NY'));

        $this->assertTrue($address->getState() instanceof \XLite\Model\State, 'State checking');
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

        $address->setCountry(\XLite\Core\Database::getRepo('XLite\Model\Country')->find('US'));

        $this->assertTrue($address->getCountry() instanceof \XLite\Model\Country, 'Country checking');
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
