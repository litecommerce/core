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
 * @subpackage Web
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

class XLite_Web_Customer_UserDetails extends XLite_Web_Customer_ACustomer
{
    /**
     * Users data for testing
     * 
     * @var    array
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $users = array(
        // Administrator
        1 => array(
            'login'    => 'master',
            'password' => 'master', // md5(master) = eb0a191797624dd3a48fa681d3061212
            'email'    => 'rnd_tester@cdev.ru',
        ),
        // Customer
        2 => array(
            'login'    => 'user2011',
            'password' => 'demo', // md5(demo) = fe01ce2a7fbac8fafaed7c982a04e229
            'email'    => 'rnd_tester05@cdev.ru',
        ),
    );


    /**
     * Test on simple update own profile by administrator
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testUpdate1()
    {
        $user = $this->getUser(1);
        
        $this->loginUser($user);

        $this->open('user/1/edit');

        $this->clickAndWait('css=#edit-submit');

        if ($this->isElementPresent('//div[@id="console"]/div[@class="messages error"]')) {
            $message = $this->getText('//div[@id="console"]/div[@class="messages error"]');
            $this->assertNull($message, 'Check for error messages');
        }
    }

    /**
     * Test on update own profile by administrator with modification of some data
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testUpdate2()
    {
        $user = $this->getUser(1);
        
        $this->loginUser($user);

        $this->open('user/1/edit');

        $email = 'rnd_tester' . time() . '@cdev.ru';

        // Specify current password
        $this->type('css=#edit-current-pass', $user['password']);

        // TODO: uncomment password changing after this will be fixed

        // Change password
        //$this->type('css=#edit-pass-pass1', $user['password']);
        //$this->type('css=#edit-pass-pass2', $user['password']);

        // Change email
        $this->type('css=#edit-mail', $email);

        $this->clickAndWait('css=#edit-submit');

        if ($this->isElementPresent('//div[@id="console"]/div[@class="messages error"]')) {
            $message = $this->getText('//div[@id="console"]/div[@class="messages error"]');
            $this->assertNull($message, 'Check for error messages #1');
        }

        // Check that email is modified successfully 
        $this->assertEquals(
            $email,
            $this->getJSExpression('jQuery("#edit-mail").val()'),
            'Checking changed email'
        );

        // Revert changes back

        // Specify current password
        $this->type('css=#edit-current-pass', $user['password']);

        //$this->type('css=#edit-pass-pass1', $user['password']);
        //$this->type('css=#edit-pass-pass2', $user['password']);
        $this->type('css=#edit-mail', $user['email']);

        $this->clickAndWait('css=#edit-submit');

        if ($this->isElementPresent('//div[@id="console"]/div[@class="messages error"]')) {
            $message = $this->getText('//div[@id="console"]/div[@class="messages error"]');
            $this->assertNull($message, 'Check for error messages #2');
        }

        // Check that email is modified successfully 
        $this->assertEquals(
            $user['email'],
            $this->getJSExpression('jQuery("#edit-mail").val()'),
            'Check reverted email'
        );
    }

    /**
     * Test on user creation, then test on update this user's profile by the administrator and created user
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testCreateUser1()
    {
        $user = $this->getUser(1);

        $this->loginUser($user);

        // Create new user with an administrator permissions and disabled status

        $this->open('admin/people/create');

        $user2 = $this->getUser(2);

        //Fill profile details form 
        $this->type('css=#edit-name', $user2['login']);
        $this->type('css=#edit-mail', $user2['email']);
        $this->type('css=#edit-pass-pass1', $user2['password']);
        $this->type('css=#edit-pass-pass2', $user2['password']);

        $this->check('css=#edit-status-0'); // User status is blocked 
        $this->check('css=#edit-roles-3'); // User role - administrator

        $this->clickAndWait('css=#edit-submit');

        if ($this->isElementPresent('//div[@id="console"]/div[@class="messages error"]')) {
            $message = $this->getText('//div[@id="console"]/div[@class="messages error"]');
            $this->assertNull($message, 'Check for error messages #1');
        }

        $this->assertElementPresent(
            '//div[@id="console"]/div[@class="messages status"]//descendant::a/em[text()="' . $user2['login']. '"]',
            'Check that link to created user profile is presented'
        );

        $linkHref = $this->getJSExpression('jQuery("#console a").attr("href")');

        $this->assertNotNull($linkHref, 'Check that href of link to the profile is not null');

        if (preg_match('/user\/(\d+)/', $linkHref, $match)) {
            $userId = $match[1];
        }

        $this->assertTrue(intval($userId) == $userId, 'Check that $userId value is integer (' . $userId . ')');

        $newProfile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('cms_profile_id' => $userId));

        $this->assertNotNull($newProfile, 'Check that new profile is not null');

        $this->assertEquals($user2['email'], $newProfile->getLogin(), 'Check that email/login of new user profile in LC is the same as in Drupal');
        $this->assertTrue($newProfile->isAdmin(), 'Check that new user is LC administrator');
        $this->assertFalse($newProfile->isEnabled(), 'Check that new user account is disabled in LC');

        $profileId = $newProfile->getProfileId();

        // Edit created user: activate it and remove administrator permissions

        $this->open('user/' . $userId . '/edit');

        $this->check('css=#edit-status-1'); // User status is active 
        $this->uncheck('css=#edit-roles-3'); // Reset user role - administrator

        $this->clickAndWait('css=#edit-submit');

        if ($this->isElementPresent('//div[@id="console"]/div[@class="messages error"]')) {
            $message = $this->getText('//div[@id="console"]/div[@class="messages error"]');
            $this->assertNull($message, 'Check for error messages #2');
        }

        // Detach object to get this one again from database
        $newProfile->detach();

        $newProfile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);

        $this->assertNotNull($newProfile, 'Check that new profile is not null');

        $this->assertEquals($user2['email'], $newProfile->getLogin(), 'Check that email/login of new user profile in LC is the same as in Drupal');
        $this->assertFalse($newProfile->isAdmin(), 'Check that new user is not LC administrator (profile_id = ' . $profileId . ', status = ' . $newProfile->getAccessLevel() . ')');
        $this->assertTrue($newProfile->isEnabled(), 'Check that new user account is enabled in LC (profile_id = ' . $profileId . ')');

        // Log in as new user and update profile

        $user = $this->getUser(2);

        $this->loginUser($user);

        $this->open('user/' . $userId . '/edit');

        $email = 'rnd_tester' . time() + 1000 . '@cdev.ru';
        $newPassword = 'newpassword';

        // Specify current password
        $this->type('css=#edit-current-pass', $user['password']);

        // TODO: uncomment password changing after this will be fixed

        // Change password
        //$this->type('css=#edit-pass-pass1', $newPassword);
        //$this->type('css=#edit-pass-pass2', $newPassword);

        // Change email
        $this->type('css=#edit-mail', $email);

        $this->clickAndWait('css=#edit-submit');

        if ($this->isElementPresent('//div[@id="console"]/div[@class="messages error"]')) {
            $message = $this->getText('//div[@id="console"]/div[@class="messages error"]');
            $this->assertNull($message, 'Check for error messages');
        }

        // Check that email is modified successfully 
        $this->assertEquals(
            $email,
            $this->getJSExpression('jQuery("#edit-mail").val()'),
            'Checking changed email'
        );
    }

    /**
     * Test on password complexity checking
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testPassword()
    {
        $user = $this->getUser(1);
        
        $this->loginUser($user);

        $this->open('user/1/edit');

        // Check password strength
        $this->typeKeys(
            'css=#edit-pass-pass1',
            '123'
        );
        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass1 .password-strength .password-strength-text").html() == "Weak"',
            3000,
            'check Weak label'
        );

        $this->typeKeys(
            'css=#edit-pass-pass1',
            '123lakjsdhf'
        );
        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass1 .password-strength .password-strength-text").html() == "Good"',
            3000,
            'check Good label'
        );

        $this->typeKeys(
            'css=#edit-pass-pass1',
            '123lakjsdhf(*&%A'
        );
        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass1 .password-strength .password-strength-text").html() == "Strong"',
            3000,
            'check Strong label'
        );

        // Check password confirm
        $this->typeKeys(
            'css=#edit-pass-pass1',
            'aaa'
        );
        $this->typeKeys(
            'css=#edit-pass-pass2',
            'bbb'
        );
        $this->getJSExpression('jQuery(".form-item-pass-pass2 input").keyup()');

        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass2 .password-confirm .error").html() == "no"',
            3000,
            'check "no" label'
        );

        $this->typeKeys(
            'css=#edit-pass-pass2',
            'aaa'
        );
        $this->getJSExpression('jQuery(".form-item-pass-pass2 input").keyup()');

        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass2 .password-confirm .ok").html() == "yes"',
            3000,
            'check "yes" label'
        );

        // Submit wrong password
        $this->type('css=#edit-pass-pass1', 'master1');
        $this->type('css=#edit-pass-pass2', 'master2');

        $this->clickAndWait('css=#edit-submit');

        $this->assertJqueryPresent('.messages.error h2', 'check errors');
    }


    /**
     * Return specified user data from an array $users
     * 
     * @param integer $id User index in the $users array
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getUser($id)
    {
        return $this->users[$id];
    }

    /**
     * Log in user with specified data
     * 
     * @param array $user Cell of $users array
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function loginUser($user)
    {
        $this->logIn($user['login'], $user['password']);

        // TODO: add checking if profile in Drupal is synchronized with profile in LC
    }
}
