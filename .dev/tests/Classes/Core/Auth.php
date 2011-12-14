<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
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

/**
 * XLite_Tests_Core_Auth
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 * @resource profiles
 */
class XLite_Tests_Core_Auth extends XLite_Tests_TestCase
{
    static $admin = array('email' => 'rnd_tester@cdev.ru', 'password' => 'master');
    static $guest = array('email' => 'rnd_tester@rrf.ru', 'password' => 'guest');

    /**
     * testAddSessionVarToClear
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testAddSessionVarToClear()
    {
        $sessionVarName = 'testVar';

        \XLite\Core\Auth::getInstance()->addSessionVarToClear($sessionVarName);

        $vars = \XLite\Core\Auth::getInstance()->getSessionVarsToClear();

        $this->assertTrue(in_array($sessionVarName, $vars, 'Check session <vars to clear> setting up'));
    }

    /**
     * testLogin
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testLogin()
    {
        // Test #1
        $result = \XLite\Core\Auth::getInstance()->login(null, null, null);

        $this->assertEquals(\XLite\Core\Auth::RESULT_ACCESS_DENIED, $result, 'Test #1');

        // Test #2
        $result = \XLite\Core\Auth::getInstance()->login(null, null, md5('testhashstring'));

        $this->assertEquals(\XLite\Core\Auth::RESULT_ACCESS_DENIED, $result, 'Test #1');

        // Test #3
        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@rrf.ru', null);

        $this->assertEquals(\XLite\Core\Auth::RESULT_ACCESS_DENIED, $result, 'Test #3');

        // Test #4
        $result = \XLite\Core\Auth::getInstance()->login(null, 'guest');

        $this->assertEquals(\XLite\Core\Auth::RESULT_ACCESS_DENIED, $result, 'Test #4');

        // Test #5
        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@rrf.ru', 'guest');

        $this->assertTrue($result instanceof \XLite\Model\Profile, 'Test #5');
        $this->assertEquals(2, $result->getProfileId(), 'Test #5: checking profile_id');

        // Test #6
        $newProfile = $result->cloneEntity();
        $newProfile->setLogin('rnd_tester02@rrf.ru');
        $newProfile->disable();
        $newProfile->update();

        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester02@rrf.ru', 'guest');

        $this->assertEquals(\XLite\Core\Auth::RESULT_ACCESS_DENIED, $result, 'Test #6');

        // Test #7
        \XLite\Core\Request::getInstance()->anonymous = true;
        \XLite\Model\Cart::getInstance()->setOrderId(2);

        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@rrf.ru', 'guest');

        \XLite\Core\Request::getInstance()->anonymous = null;
        \XLite\Model\Cart::getInstance()->setOrderId(null);

        $this->assertTrue($result instanceof \XLite\Model\Profile, 'Test #7');
        $this->assertEquals(4, $result->getProfileId(), 'Test #7: checking profile_id');

        // Test #8
        $hashString = 'testHashString';

        \XLite\Core\Auth::getInstance()->setSecureHash($hashString);

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(2); // Same profile

        $profile->setPassword('testpassword'); // Unencrypted password
        if ($profile->getOrder()) {
            $profile->getOrder()->setProfile(null);
        }
        \XLite\Core\Database::getEM()->flush();

        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@rrf.ru', 'testpassword', $hashString); // Login by email/hash

        $this->assertTrue($result instanceof \XLite\Model\Profile, 'Test #8');
        $this->assertEquals(2, $result->getProfileId(), 'Test #8: checking profile_id');

        $profile->setPassword(self::$guest['password']);
        \XLite\Core\Auth::getInstance()->setSecureHash('');

   }

    /**
     * testLogoff
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testLogoff()
    {
        $this->doRestoreDb();

        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@rrf.ru', 'guest');

        $this->assertTrue($result instanceof \XLite\Model\Profile, 'Checking if user is logged in');
        $this->assertEquals(2, $result->getProfileId(), 'Checking profile_id');

        $session = \XLite\Core\Session::getInstance();

        $this->assertEquals(2, $session->get('profile_id'), 'Checking profile_id in session vars');

        \XLite\Core\Auth::getInstance()->logoff();

        $this->assertNull($session->get('profile_id'), 'Checking profile_id in session after logoff()');
    }

    /**
     * testIsLogged
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsLogged()
    {
        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@rrf.ru', 'guest');

        $this->assertTrue($result instanceof \XLite\Model\Profile, 'Checking if user is logged in');
        $this->assertEquals(2, $result->getProfileId(), 'Checking profile_id');

        $this->assertTrue(\XLite\Core\Auth::getInstance()->isLogged(), 'Check if user is logged in');

        \XLite\Core\Auth::getInstance()->logoff();

        $this->assertFalse(\XLite\Core\Auth::getInstance()->isLogged(), 'Check if user is not logged in');
    }

    /**
     * testGetProfile
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetProfile()
    {
        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@rrf.ru', 'guest');

        $this->assertTrue($result instanceof \XLite\Model\Profile, 'Checking if user is logged in');
        $this->assertEquals(2, $result->getProfileId(), 'Checking profile_id');

        $profile = \XLite\Core\Auth::getInstance()->getProfile();

        $this->assertEquals(2, $profile->getProfileId(), 'Checking profile_id registered in session');

        $profile = \XLite\Core\Auth::getInstance()->getProfile(1);

        $this->assertNull($profile, 'Checking if profile is null');
    }

    /**
     * testCheckProfile
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testCheckProfile()
    {
        // Test #1
        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@rrf.ru', 'guest'); // Login customer

        $this->assertTrue($result instanceof \XLite\Model\Profile, 'Checking if user is logged in');
        $this->assertEquals(2, $result->getProfileId(), 'Checking profile_id');

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(2); // Same profile

        $checkResult = \XLite\Core\Auth::getInstance()->checkProfile($profile);

        $this->assertTrue($checkResult, 'Test #1: Check if checkProfile() returned true');

        // Test #2
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(1); // Admin profile

        $checkResult = \XLite\Core\Auth::getInstance()->checkProfile($profile);

        $this->assertFalse($checkResult, 'Test #2: Check if checkProfile() returned false');

        // Test #3
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(4); // Customer profile related to order

        $checkResult = \XLite\Core\Auth::getInstance()->checkProfile($profile);

        $this->assertFalse($checkResult, 'Test #3: Check if checkProfile() returned false');

        // Test #4
        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@cdev.ru', 'master'); // Administrator login

        $this->assertTrue($result instanceof \XLite\Model\Profile, 'Checking if user is logged in');
        $this->assertEquals(1, $result->getProfileId(), 'Checking profile_id');

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(1); // Same profile

        $checkResult = \XLite\Core\Auth::getInstance()->checkProfile($profile);

        $this->assertTrue($checkResult, 'Test #4: Check if checkProfile() returned true');

        // Test #5
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(2); // Customer profile

        $checkResult = \XLite\Core\Auth::getInstance()->checkProfile($profile);

        $this->assertTrue($checkResult, 'Test #5: Check if checkProfile() returned true');
    }

    /**
     * testIsAdmin
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsAdmin()
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(2); // Customer profile

        $result = \XLite\Core\Auth::getInstance()->isAdmin($profile);

        $this->assertFalse($result, 'Test #1: Checking if customer profile will be recognized as non-admin');

        // Test #2
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(1); // Admin profile

        $result = \XLite\Core\Auth::getInstance()->isAdmin($profile);

        $this->assertTrue($result, 'Test #2: Checking if admin profile will be recognized as admin');
    }

    /**
     * testGetAccessLevel
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetAccessLevel()
    {
        // Test #1
        $result = \XLite\Core\Auth::getInstance()->getAccessLevel('testType'); // Non-existing user type

        $this->assertNull($result, 'Test #1');

        // Test #2
        $result = \XLite\Core\Auth::getInstance()->getAccessLevel('Customer');

        $this->assertEquals(\XLite\Core\Auth::getInstance()->getCustomerAccessLevel(), $result, 'Test #2');

        // Test #3
        $result = \XLite\Core\Auth::getInstance()->getAccessLevel('Admin');

        $this->assertEquals(\XLite\Core\Auth::getInstance()->getAdminAccessLevel(), $result, 'Test #3');

        // Test #4
        $result = \XLite\Core\Auth::getInstance()->getAccessLevel('customer'); // Lower-case initial char

        $this->assertNull($result, 'Test #4');
    }

    /**
     * testGetAdminAccessLevel
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetAdminAccessLevel()
    {
        $this->assertEquals(100, \XLite\Core\Auth::getInstance()->getAdminAccessLevel(), 'Checking an admin access level value');
    }

    /**
     * testGetCustomerAccessLevel
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetCustomerAccessLevel()
    {
        $this->assertEquals(0, \XLite\Core\Auth::getInstance()->getCustomerAccessLevel(), 'Checking a customer access level value');
    }

    /**
     * testGetAccessLevelsList
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetAccessLevelsList()
    {
        $expectedResult = array(
            'customer' => 0,
            'admin'    => 100,
        );

        $result = \XLite\Core\Auth::getInstance()->getAccessLevelsList();

        $this->assertEquals($expectedResult, $result, 'Checking an getAccessLevelsList() result');
    }

    /**
     * testGetUserTypesRaw
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testGetUserTypesRaw()
    {
        $expectedResult = array(
            0   => 'Customer',
            100 => 'Admin',
        );

        $result = \XLite\Core\Auth::getInstance()->getUserTypesRaw();

        $this->assertEquals($expectedResult, $result, 'Checking an getUserTypesRaw() result');
    }

    /**
     * testSetSecureHash
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSetSecureHash()
    {
        $hashString = 'testHashString';

        \XLite\Core\Auth::getInstance()->setSecureHash($hashString);

        $cell = \XLite\Core\Auth::SESSION_SECURE_HASH_CELL;

        $result = \XLite\Core\Session::getInstance()->$cell;

        $this->assertEquals($hashString, $result, 'Checking setSecureHash() result');

        \XLite\Core\Auth::getInstance()->setSecureHash('');
    }

    /**
     * testRemindLogin
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testRemindLogin()
    {
        $result = \XLite\Core\Auth::getInstance()->remindLogin();

        $this->assertTrue(is_string($result), 'Checking that remindLogin() returns a string value');
    }

    /**
     * testLoginAdministrator
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testLoginAdministrator()
    {
         // Test #1
        $result = \XLite\Core\Auth::getInstance()->loginAdministrator('rnd_tester@rrf.ru', 'guest'); // Customer login

        $this->assertEquals(\XLite\Core\Auth::RESULT_ACCESS_DENIED, $result, 'Test #1');

         // Test #2
        $result = \XLite\Core\Auth::getInstance()->loginAdministrator('rnd_tester@cdev.ru', 'master'); // Administrator login

        $this->assertTrue($result instanceof \XLite\Model\Profile, 'Test #2');
        $this->assertEquals(1, $result->getProfileId(), 'Test #2: checking profile_id');
    }

    /**
     * testIsAuthorized
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testIsAuthorized()
    {
        $result = \XLite\Core\Auth::getInstance()->login('rnd_tester@rrf.ru', 'guest');

        $resource = new \XLite\Controller\Admin\Countries();

        $result = \XLite\Core\Auth::getInstance()->isAuthorized($resource);

        $this->assertFalse($result, 'Checking access to the country section');

        $resource = new \XLite\Controller\Admin\Login();

        $result = \XLite\Core\Auth::getInstance()->isAuthorized($resource);

        $this->assertTrue($result, 'Checking access to the login section');
    }
}
