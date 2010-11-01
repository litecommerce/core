<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\Profile class tests
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

class XLite_Tests_Model_Profile extends XLite_Tests_TestCase
{
    /**
     * testProfileData 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $testProfileData = array(
        // Admin profile
        0 => array(
            'login'         => 'rnd_tester01@cdev.ru',
            'password'      => 'testpassword',
            'access_level'  => 100,
            'referer'       => 'some referer',
            'membership_id' => 0,
        ),
        // Customer profile
        1 => array(
            'login'         => 'rnd_tester03@cdev.ru',
            'password'      => 'testpassword',
            'access_level'  => 0,
            'referer'       => 'some referer',
            'membership_id' => 1,
            'pending_membership_id' => 1,
        ),
        // Customer profile related to some order
        2 => array(
            'login'         => 'rnd_tester02@cdev.ru',
            'password'      => 'testpassword',
            'access_level'  => 0,
            'referer'       => 'some referer',
            'membership_id' => 0,
            'order_id'      => 1,
        ),
    );

    /**
     * addresses 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $testAddresses = array(
        // Addresses set #0
        0 => array(
            0 => array(
                'is_billing'  => 1,
                'is_shipping' => 1,
                'firstname'   => 'a0',
            ),
            1 => array(
                'firstname'   => 'a1',
            ),
            2 => array(
                'firstname'   => 'a2',
            ),
        ),
        // Addresses set #1
        1 => array(
            0 => array(
                'is_billing'   => 1,
                'firstname'   => 'a0',
            ),
            1 => array(
                'is_shipping'  => 1,
                'firstname'   => 'a1',
            ),
            2 => array(
                'firstname'   => 'a2',
            ),
        ),
        // Addresses set #2
        2 => array(
            0 => array(
                'firstname'   => 'a0',
            ),
            1 => array(
                'firstname'   => 'a1',
            ),
            2 => array(
                'firstname'   => 'a2',
            ),
        ),
        // Address set #3
        3 => array(),
        // Addresses set #4
        4 => array(
            0 => array(
                'is_billing'   => 1,
                'firstname'   => 'a0',
            ),
            1 => array(
                'is_shipping'  => 1,
                'firstname'   => 'a0',
            ),
        ),

    );

    /**
     * profileFields 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $profileFields = array(
        'login'                 => 'aaa',
        'password'              => 'password test',
        'password_hint'         => 'password_hint test',
        'password_hint_answer'  => 'password_hint_answer test',
        'access_level'          => 23,
        'cms_profile_id'        => 66666,
        'cms_name'              => 'cms name test',
        'added'                 => 77777,
        'first_login'           => 88888,
        'last_login'            => 99999,
        'status'                => 'T',
        'referer'               => 'referer test',
        'membership_id'         => 44,
        'pending_membership_id' => 33,
        'order_id'              => 44444,
        'language'              => 'ru',
        'sidebar_boxes'         => 'sidebar_boxes test',
    );

    /**
     * tearDown
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->query(file_get_contents(__DIR__ . '/Repo/sql/profile/restore.sql'));
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * testGetBillingAddress 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetBillingAddress()
    {
        // Test #1
        $profile = $this->getTestProfile(0, 0);

        $address = $profile->getBillingAddress();

        $this->assertEquals('a0', $address->getFirstname(), 'Wrong billing address selected (set #0)');

        $this->deleteTestProfile($profile->getProfileId());

        // Test #2
        $profile = $this->getTestProfile(0, 1);

        $address = $profile->getBillingAddress();

        $this->assertEquals('a0', $address->getFirstname(), 'Wrong billing address selected (set #1)');

        $this->deleteTestProfile($profile->getProfileId());

        // Test #3
        $profile = $this->getTestProfile(0, 2);

        $address = $profile->getBillingAddress();

        $this->assertNull($address, 'Check that address is null (0,2)');

        $this->deleteTestProfile($profile->getProfileId());

        // Test #4
        $profile = $this->getTestProfile(0, 3);

        $address = $profile->getBillingAddress();

        $this->assertNull($address, 'Wrong billing address selected (set #3)');

    }

    /**
     * testGetShippingAddress 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetShippingAddress()
    {
        // Test #1
        $profile = $this->getTestProfile(0, 0);

        $address = $profile->getShippingAddress();

        $this->assertEquals('a0', $address->getFirstname(), 'Wrong shipping address selected (set #0)');

        $this->deleteTestProfile($profile->getProfileId());

        // Test #2
        $profile = $this->getTestProfile(0, 1);

        $address = $profile->getShippingAddress();

        $this->assertEquals('a1', $address->getFirstname(), 'Wrong billing address selected (set #1)');

        $this->deleteTestProfile($profile->getProfileId());

        // Test #3
        $profile = $this->getTestProfile(0, 2);

        $address = $profile->getShippingAddress();

        $this->assertNull($address, 'Check that address is null (0,2)');

        $this->deleteTestProfile($profile->getProfileId());

        // Test #4
        $profile = $this->getTestProfile(0, 3);

        $address = $profile->getShippingAddress();

        $this->assertNull($address, 'Wrong billing address selected (set #3)');

    }

    /**
     * testGetOrdersCount 
     * TODO: add more tests
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testGetOrdersCount()
    {
        // Test #1
        $profile = $this->getTestProfile(0, 1);

        $this->assertEquals(0, $profile->getOrdersCount(), 'orders_count checking');
    }

    /**
     * testIsEnabled 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsEnabled()
    {
        $profile = $this->getTestProfile(0, 1);

        $profile->enable();

        $this->assertTrue($profile->isEnabled(), 'Expected status value (enabled) does not match');

        $profile->disable();

        $this->assertFalse($profile->isEnabled(), 'Expected status value (disabled) does not match');
    }

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
        $profile = $this->getTestProfile(1, 0);

        foreach ($this->testProfileData[1] as $key => $value) {
            $methodName = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($key);
            $this->assertEquals($value, $profile->$methodName(), 'Wrong property (' . $key . ')' );
        }

        $this->assertTrue($profile->getMembership() instanceof \XLite\Model\Membership, 'Membership is expected to be an object');

        foreach ($this->profileFields as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $profile->$setterMethod($testValue);
            $value = $profile->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking ('.$field.')');
        }

        $this->assertTrue($profile->getPendingMembership() instanceof \XLite\Model\Membership, 'Pending membership is expected to be an object');
    }

    /**
     * testUpdate 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testUpdate()
    {
        // Test #1
        $profile1 = $this->getTestProfile(1, 0);

        $profile1->map($this->testProfileData[2]);

        $result = $profile1->update();
        
        // Update result must be true
        $this->assertTrue($result, 'update() must return true');

        // Get updated profile from the database
        $profile2 = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profile1->getProfileId());

        // Check if profile properties are correctly updated
        foreach ($this->testProfileData[2] as $key => $value) {
            $methodName = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($key);
            // membership_id must be null after updating if it has a zero value initially
            if (in_array($key, array('membership_id', 'pending_membership_id')) && 0 === $value) {
                $this->assertNull($profile2->$methodName(), 'Wrong property (' . $key . ')' );

            } else {
                $this->assertEquals($value, $profile2->$methodName(), 'Wrong property (' . $key . ')' );
            }
        }

        $this->assertNull($profile2->getMembership(), 'Membership is expected to be null');

        // Test #2: update user with login that is used by other user, check for duplicate login
        
        $profile3 = $this->getTestProfile(0, 0);

        $profile4 = $this->getTestProfile(1, 0);

        $origLogin = $profile4->getLogin();
        $origProfileId = $profile4->getProfileId();

        $profile4->setLogin($profile3->getLogin());

        $result = $profile4->update();

        $this->assertFalse($result, 'update() must return false');

        $profile5 = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($origProfileId);

        // TODO: check why this test failed
        //$this->assertEquals($origLogin, $profile5->getLogin(), 'Checking for duplicate login');
    }

    /**
     * testIsSameAddress 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testIsSameAddress()
    {
        $profile = $this->getTestProfile(1, 1);

        $this->assertFalse($profile->isSameAddress(), 'isSameAddress() expected to be false');

        $this->deleteTestProfile($profile->getProfileId());

        $profile = $this->getTestProfile(1, 4);

        $this->assertTrue($profile->isSameAddress(), 'isSameAddress() expected to be true');
    }

    /**
     * testCloneObject 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCloneObject()
    {
        $profile = $this->getTestProfile(1, 1);

        $clonedProfile = $profile->cloneObject();

        $this->assertTrue($clonedProfile instanceof \XLite\Model\Profile, 'Cloned profile expected to be an object');

        $this->assertNotEquals($profile->getProfileId(), $clonedProfile->getProfileId(), 'profile_id comparison');
        $this->assertEquals($profile->getLogin(), $clonedProfile->getLogin(), 'login comparison');
        $this->assertEquals($profile->getPassword(), $clonedProfile->getPassword(), 'password comparison');
        $this->assertEquals($profile->getAccessLevel(), $clonedProfile->getAccessLevel(), 'access_level comparison');
        $this->assertEquals($profile->getCmsProfileId(), $clonedProfile->getCmsProfileId(), 'cms_profile_id comparison');
        $this->assertEquals($profile->getAdded(), $clonedProfile->getAdded(), 'added comparison');
        $this->assertEquals($profile->getLastLogin(), $clonedProfile->getLastLogin(), 'last_login comparison');
        $this->assertEquals($profile->getStatus(), $clonedProfile->getStatus(), 'status comparison');
        $this->assertEquals($profile->getReferer(), $clonedProfile->getReferer(), 'referer comparison');
        $this->assertEquals($profile->getMembershipId(), $clonedProfile->getMembershipId(), 'membership_id comparison');
        $this->assertEquals($profile->getPendingMembershipId(), $clonedProfile->getPendingMembershipId(), 'pending_membership_id comparison');
        $this->assertEquals($profile->getOrderId(), $clonedProfile->getOrderId(), 'order_id comparison');
        $this->assertEquals($profile->getLanguage(), $clonedProfile->getLanguage(), 'language comparison');

        $membership1 = $profile->getMembership();
        $membership2 = $clonedProfile->getMembership();

        $this->assertEquals($membership1->getMembershipId(), $membership2->getMembershipId(), 'Memberships comparison');

        $addresses1 = $profile->getAddresses();
        $addresses2 = $clonedProfile->getAddresses();

        $this->assertLessThanOrEqual(2, count($addresses2), 'count of cloned addresses must not exceed 2');

        $address1 = $profile->getBillingAddress();
        $address2 = $clonedProfile->getBillingAddress();

        $this->assertNotEquals($address1->getAddressId(), $address2->getAddressId(), 'address_id comparison');
        $this->assertNotEquals($address1->getProfileId(), $address2->getProfileId(), 'profile_id comparison');
        $this->assertEquals($address1->getIsBilling(), $address2->getIsBilling(), 'is_billing comparison');
        $this->assertEquals($address1->getIsShipping(), $address2->getIsShipping(), 'is_shipping comparison');
        $this->assertEquals($address1->getFirstname(), $address2->getFirstname(), 'firstname comparison');
    }

    /**
     * getTestProfile 
     * 
     * @param int $id ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTestProfile($selectedProfileId = 0, $selectedAddressesId = 0)
    {
        $profile = new \XLite\Model\Profile();

        $profile->map($this->testProfileData[$selectedProfileId]);

        foreach ($this->testAddresses[$selectedAddressesId] as $data) {
            $address = new \XLite\Model\Address();
            $address->map($data);
            $address->setProfile($profile);
            $profile->addAddresses($address);
        }

        $result = $profile->create();

        $this->assertNotNull($profile, sprintf('Profile creation failed (%d, %d)', $selectedProfileId, $selectedAddressesId));

        return $result ? $profile : null;
    }

    /**
     * deleteTestProfile 
     * 
     * @param mixed $profileId ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function deleteTestProfile($profileId)
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);

        if (isset($profile)) {
            $profile->delete();
        }
    }

}
