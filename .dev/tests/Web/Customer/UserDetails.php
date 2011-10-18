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
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

class XLite_Web_Customer_UserDetails extends XLite_Web_Customer_ACustomer
{
    /**
     * Admin data for testing
     *
     * @var    array
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $admin = array(
            'id' => 1,
            'login'    => 'master',
            'password' => 'master', // md5(master) = eb0a191797624dd3a48fa681d3061212
            'email'    => 'rnd_tester@cdev.ru',
    );

    /**
     * Users data for testing
     *
     * @var    array
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $customers=  array(
        array(
            'login'    => 'user2011',
            'password' => 'demo', // md5(demo) = fe01ce2a7fbac8fafaed7c982a04e229
            'email'    => 'rnd_tester05@cdev.ru',
        ),
        array(
            'login'    => 'user2012',
            'password' => 'demo', // md5(demo) = fe01ce2a7fbac8fafaed7c982a04e229
            'email'    => 'rnd_tester05@rrf.ru',
        )
        );


    /**
     * Role name for testing
     *
     * @var    string
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected $roleName = 'test role';

    /**
     * Test on simple update own profile by administrator
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testEmptyUpdate()
    {
        $this->loginUser($this->admin);

        $this->open('user/'.$this->admin['id'].'/edit');

        $this->assertElementPresent('id=edit-submit', 'Check if Update button presented');

        $this->clickAndWait('id=edit-submit');

        $this->checkForErrorMessages('Check for error messages');
    }

    /**
     * Test on update own profile by administrator with modification of some data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testUpdate()
    {
        $this->_testUpdateUser($this->admin, true);
    }

    /**
     * Test on update own profile. Fake test %)
     * @param array $user
     * @param bool $revert
     * @return
     *
     */
    private  function _testUpdateUser(array $user, $revert = false)
    {
        $this->loginUser($user);

        $this->open('user/'.$user['id'].'/edit');

        $email = 'rnd_tester' . time() . '@cdev.ru';

        // Specify current password
        $this->type('id=edit-current-pass', $user['password']);

        // Change password
        $this->type('id=edit-pass-pass1', $user['password']);
        $this->type('id=edit-pass-pass2', $user['password']);

        // Change email
        $this->type('id=edit-mail', $email);

        $this->clickAndWait('id=edit-submit');

        $this->checkForErrorMessages('Check for error messages #1');

        // Check that email is modified successfully
        $this->assertEquals(
            $email,
            $this->getJSExpression('jQuery("#edit-mail").val()'),
            'Checking changed email'
        );
        if (!$revert)
            return;
        // Revert changes back
        //TODO: What for? We can revert it on teardown if no restoreDB

        // Specify current password
        $this->type('id=edit-current-pass', $user['password']);

        $this->type('id=edit-pass-pass1', $user['password']);
        $this->type('id=edit-pass-pass2', $user['password']);
        $this->type('id=edit-mail', $user['email']);

        $this->clickAndWait('id=edit-submit');

        $this->checkForErrorMessages('Check for error messages #2');

        // Check that email is modified successfully
        $this->assertEquals(
            $user['email'],
            $this->getJSExpression('jQuery("#edit-mail").val()'),
            'Check reverted email'
        );
    }



    /**
     * Test on user creation
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testCreateUser()
    {

        if (!$this->isAdmin())
            $this->loginUser($this->admin);

        //Create admin and customer
        self::$customers[0]['id'] = $this->createUser(self::$customers[0],true);
        self::$customers[1]['id'] = $this->createUser(self::$customers[1],false);


        //Test created user activation, login and profile
        $this->_testCreatedUser(self::$customers[0]);

    }

    /**
     * Test on update user profile by the administrator and created user
     * @param array $user
     * @return void
     */
    private function _testCreatedUser(array $user){


        $userId = $user['id'];

        // Check if user profile has also been on LC side
        $newProfile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('cms_profile_id' => $userId));

        $this->assertNotNull($newProfile, 'Check that new profile is not null');

        $this->assertEquals($user['email'], $newProfile->getLogin(), 'Check that email/login of new user profile in LC is the same as in Drupal');
        $this->assertTrue($newProfile->isAdmin(), 'Check that new user is LC administrator');
        $this->assertFalse($newProfile->isEnabled(), 'Check that new user account is disabled in LC');

        $profileId = $newProfile->getProfileId();

        // Edit created user: activate it and remove administrator permissions

        $this->open('user/' . $userId . '/edit');

        $this->assertElementPresent('id=edit-status-1', 'Check if Status radio-button is presented');
        $this->assertElementPresent('id=edit-roles-3', 'Check if Roles checkbox is presented');
        $this->assertElementPresent('id=edit-submit', 'Check if Submit button is presented');

        $this->check('id=edit-status-1'); // User status is active
        $this->uncheck('id=edit-roles-3'); // Reset user role - administrator

        $this->clickAndWait('id=edit-submit');

        $this->checkForErrorMessages('Check for error messages #3');

        // Detach object to get this one again from database
        $newProfile->detach();

        $newProfile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($profileId);

        $this->assertNotNull($newProfile, 'Check that new profile is not null');

        $this->assertEquals($user['email'], $newProfile->getLogin(), 'Check that email/login of new user profile in LC is the same as in Drupal');
        $this->assertFalse($newProfile->isAdmin(), 'Check that new user is not LC administrator (profile_id = ' . $profileId . ', status = ' . $newProfile->getAccessLevel() . ')');
        $this->assertTrue($newProfile->isEnabled(), 'Check that new user account is enabled in LC (profile_id = ' . $profileId . ')');

        // Log in as new user and update profile
        $this->_testUpdateUser($user);
    }




    /**
     * Test on user creation, then test on update this user's profile by the administrator and created user
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     *
     */
    public function testRoles()
    {

        $userIds = array_map(function($customer) { return $customer['id'];}, self::$customers);

        if(!$this->isAdmin())
            $this->loginUser($this->admin);

        // Create new role for testing

        $this->open('admin/people/permissions/roles');

        $this->assertElementPresent('id=edit-name', 'Check if Role name input field is presented');
        $this->assertElementPresent('id=edit-add', 'Check if Add button is presented');

        $this->type('id=edit-name', $this->roleName);

        $this->clickAndWait('id=edit-add');

        // Assign role Id
        $roleId = 4;

        $this->assertElementPresent('//a[contains(@href, "people/permissions/roles/edit/' . $roleId . '") and contains(text(), "edit role")]');

        $linkHref = $this->getAttribute('//a[contains(@href, "people/permissions/roles/edit/' . $roleId . '") and contains(text(), "edit role")]@href');

        $this->assertNotNull($linkHref, 'Check that href of link to the role is not null');

        // Assign new role to the users #1 and #2 on users list page

        $this->open('admin/people');

        foreach ($userIds as $userId) {
            $this->assertElementPresent('id=edit-accounts-' . $userId, sprintf('Check box for user #%d not found (1)', $userId));
            $this->check('id=edit-accounts-' . $userId);
        }

        $this->assertElementPresent('id=edit-operation', 'Check if Operation dropdown box is presented');
        $this->assertElementPresent('id=edit-submit--2', 'Check if Submit button is presented');

        $this->select('id=edit-operation', 'value=add_role-' . $roleId);

        $this->clickAndWait('id=edit-submit--2');

        $this->checkForErrorMessages('Check for error messages #4');

        // Add permission 'lc admin' to the new role

        $this->open('admin/people/permissions/' . $roleId);

        $this->assertElementPresent('id=edit-' . $roleId . '-lc-admin', 'Check if Litecommerce admin permission is presented in the permissions list');
        $this->assertElementPresent('id=edit-submit', 'Check if Submit button is presented');

        $this->check('id=edit-' . $roleId . '-lc-admin');

        $this->clickAndWait('id=edit-submit');

        $this->assertChecked('id=edit-' . $roleId . '-lc-admin', 'Permission "lc admin" is not updated');
        // Check that LC profiles are admins now

        foreach ($userIds as $userId) {

            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('cms_profile_id' => $userId));

            $this->assertNotNull($profile, sprintf('Check that profile for user #%d is not null', $userId));

            $this->assertTrue($profile->isAdmin(), sprintf('Check that user #%d is LC administrator', $userId));

            $profile->detach();
        }
        // Delete role

        $this->open('admin/people/permissions/roles/edit/' . $roleId);


        $this->assertElementPresent('id=edit-delete', 'Check if Delete role button is presented');

        $this->clickAndWait('id=edit-delete'); // Click 'Delete' button


        $this->assertElementPresent('id=edit-submit', 'Check if Confirm delete role button is presented');

        $this->clickAndWait('id=edit-submit'); // Click 'Submit' button on confirmation page

        $this->checkForErrorMessages('Check for error messages #5');

        // Check that LC profile are customers now

        foreach ($userIds as $userId) {

            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('cms_profile_id' => $userId));

            $this->assertNotNull($profile, sprintf('Check that profile for user #%d is not null', $userId));

            $this->assertFalse($profile->isAdmin(), sprintf('Check that user #%d is LC customer', $userId));

            $profile->detach();
        }
    }

    /**
     * Test cancelling user profiles and deleting from LC
     *
     * @return void
     */
    public function testDeleteCustomers(){


        $userIds = array_map(function($customer) { return $customer['id'];}, self::$customers);

        if (!$this->isAdmin())
            $this->loginUser($this->admin);

        // Cancel user accounts

        $this->open('admin/people');

        foreach ($userIds as $userId) {
            $this->assertElementPresent('id=edit-accounts-' . $userId, sprintf('Check box for user #%d not found (2)', $userId));
            $this->check('id=edit-accounts-' . $userId);
        }

        $this->assertElementPresent('id=edit-operation', 'Check if Operation dropdown box is presented');
        $this->assertElementPresent('id=edit-submit--2', 'Check if Submit button is presented');

        $this->select('id=edit-operation', 'value=cancel');

        $this->clickAndWait('id=edit-submit--2'); // Click on 'Update' button in the list


        $this->assertElementPresent('id=edit-user-cancel-method--2', 'Check if Disable user account radio-button is presented');
        $this->assertElementPresent('id=edit-submit', 'Check if Submit button is presented');

        $this->check('id=edit-user-cancel-method--2'); // Select 'Disable' option

        $sleep = $this->setSleep(0);

        $this->clickAndWait('id=edit-submit'); // And submit confirmation form

        // Batch process 'Cancelling mode'

        $this->waitForCondition(
            'selenium.isElementPresent(\'//div[@class="percentage"]\') && selenium.getText(\'//div[@class="percentage"]\') == \'100%\'',
            60000,
            'Percentage of batch process does not achived the value of 100% (cancel users)'
        );

        $this->waitForPageToLoad(60000, 'Page is not reloaded after batch process finished (cancel users)');

        $this->checkForErrorMessages('Check for error messages #6');

        $this->setSleep($sleep);

        // Check that LC profiles are disabled now

        foreach ($userIds as $userId) {

            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('cms_profile_id' => $userId));

            $this->assertNotNull($profile, sprintf('Check that profile for user #%d is not null', $userId));

            $this->assertFalse($profile->isEnabled(), sprintf('Check that user #%d is disabled', $userId));

            $profile->detach();
        }

        // Delete user accounts

        $this->open('admin/people');

        foreach ($userIds as $userId) {
            $this->assertElementPresent('id=edit-accounts-' . $userId, sprintf('Check box for user #%d not found (3)', $userId));
            $this->check('id=edit-accounts-' . $userId);
        }


        $this->assertElementPresent('id=edit-operation', 'Check if Operation dropdown box is presented');
        $this->assertElementPresent('id=edit-submit--2', 'Check if Submit button is presented');

        $this->select('id=edit-operation', 'value=cancel');

        $this->clickAndWait('id=edit-submit--2'); // Click on 'Update' button in the list


        $this->assertElementPresent('id=edit-user-cancel-method--5', 'Check if Delete user account radio-button is presented');
        $this->assertElementPresent('id=edit-submit', 'Check if Submit button is presented');

        $this->check('id=edit-user-cancel-method--5'); // Select 'Delete' option

        $sleep = $this->setSleep(0);

        $this->clickAndWait('id=edit-submit'); // And submit confirmation form

        // Batch process 'Deleting mode'

        $this->waitForCondition(
            'selenium.isElementPresent(\'//div[@class="percentage"]\') && selenium.getText(\'//div[@class="percentage"]\') == \'100%\'',
            60000,
            'Percentage of batch process does not achived the value of 100% (delete users)'
        );

        $this->waitForPageToLoad(60000, 'Page is not reloaded after batch process finished (delete users)');

        $this->checkForErrorMessages('Check for error messages #7');

        $this->setSleep($sleep);

        // Check that LC profiles are not exists now

        foreach ($userIds as $userId) {

            $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findOneBy(array('cms_profile_id' => $userId));

            $this->assertNull($profile, sprintf('Check that profile for user #%d is not exists', $userId));
        }

        foreach (self::$customers as $c){
            unset($c['id']);
        }

    }

     /**
     * Test on password complexity checking
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testPassword()
    {
        if(!$this->isAdmin())
            $this->loginUser($this->admin);

        $this->open('user/'.$this->admin['id'].'/edit');

        // Check password strength
        $this->typeKeys(
            'id=edit-pass-pass1',
            '123'
        );
        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass1 .password-strength .password-strength-text").html() == "Weak"',
            3000,
            'check Weak label'
        );

        $this->typeKeys(
            'id=edit-pass-pass1',
            '123lakjsdhf'
        );
        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass1 .password-strength .password-strength-text").html() == "Good"',
            3000,
            'check Good label'
        );

        $this->typeKeys(
            'id=edit-pass-pass1',
            '123lakjsdhf(*&%A'
        );
        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass1 .password-strength .password-strength-text").html() == "Strong"',
            3000,
            'check Strong label'
        );

        // Check password confirm
        $this->typeKeys(
            'id=edit-pass-pass1',
            'aaa'
        );
        $this->typeKeys(
            'id=edit-pass-pass2',
            'bbb'
        );
        $this->getJSExpression('jQuery(".form-item-pass-pass2 input").keyup()');

        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass2 .password-confirm .error").html() == "no"',
            3000,
            'check "no" label'
        );

        $this->typeKeys(
            'id=edit-pass-pass2',
            'aaa'
        );
        $this->getJSExpression('jQuery(".form-item-pass-pass2 input").keyup()');

        $this->waitForLocalCondition(
            'jQuery(".form-item-pass-pass2 .password-confirm .ok").html() == "yes"',
            3000,
            'check "yes" label'
        );

        // Submit wrong password
        $this->type('id=edit-pass-pass1', 'master1');
        $this->type('id=edit-pass-pass2', 'master2');

        $this->clickAndWait('id=edit-submit');

        $this->assertJqueryPresent('.messages.error h2', 'check errors');
    }

    /**
     * Log in user with specified data
     *
     * @param array $user Cell of $users array
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function loginUser($user)
    {
        $this->logIn($user['login'], $user['password']);

        // TODO: add checking if profile in Drupal is synchronized with profile in LC
    }

    /**
     * Create user and return userId in Drupal
     *
     * @param array $user Cell of $users array
     *
     * @param bool $isAdmin
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function createUser($user, $isAdmin = false)
    {
        // Create new user with an administrator permissions and disabled status

        $this->open('admin/people/create');

        $this->assertElementPresent('id=edit-name', 'Check if Name input field is presented (Create user form)');
        $this->assertElementPresent('id=edit-mail', 'Check if Email input field is presented (Create user form)');
        $this->assertElementPresent('id=edit-pass-pass1', 'Check if Password input field is presented (Create user form)');
        $this->assertElementPresent('id=edit-pass-pass2', 'Check if Confirm password input field is presented (Create user form)');
        $this->assertElementPresent('id=edit-status-0', 'Check if Status radio-button is presented (Create user form)');
        $this->assertElementPresent('id=edit-roles-3', 'Check if Role checkbox is presented (Create user form)');
        $this->assertElementPresent('id=edit-submit', 'Check if Submit button is presented (Create user form)');

        //Fill profile details form
        $this->type('id=edit-name', $user['login']);
        $this->type('id=edit-mail', $user['email']);
        $this->type('id=edit-pass-pass1', $user['password']);
        $this->type('id=edit-pass-pass2', $user['password']);

        $this->check('id=edit-status-0'); // User status is blocked

        if ($isAdmin) {
            $this->check('id=edit-roles-3'); // User role - administrator
        }

        $this->clickAndWait('id=edit-submit');

        $this->checkForErrorMessages('Check for error messages #8');

        $this->assertElementPresent(
            '//div[@id="console"]/div[@class="messages status"]//descendant::a/em[text()="' . $user['login']. '"]',
            'Check that link to created user profile is presented'
        );

        $linkHref = $this->getJSExpression('jQuery("#console a").attr("href")');

        $this->assertNotNull($linkHref, 'Check that href of link to the profile is not null');

        if (preg_match('/user\/(\d+)/', $linkHref, $match)) {
            $userId = $match[1];
        }

        $this->assertTrue(intval($userId) == $userId, 'Check that $userId value is integer (' . $userId . ')');

        return $userId;
    }

    /**
     * Check for error messages
     *
     * @param string $msg Custom message to display in report if test failed
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     */
    protected function checkForErrorMessages($msg)
    {
        if ($this->isElementPresent('//div[@id="console"]/div[@class="messages error"]')) {
            $message = $this->getText('//div[@id="console"]/div[@class="messages error"]');
            if (!(defined('TESTS_IGNORE_EMAIL_ERRORS') && preg_match('/' . preg_quote('Unable to send e-mail') . '/', $message))) {
                $this->assertNull($message, $msg);
            }
        }
    }
}
