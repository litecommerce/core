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

class XLite_Tests_Core_Mailer extends XLite_Tests_TestCase
{
    /**
     * This email is used as a user email
     */
    const TESTER_EMAIL = 'rnd_tester05@cdev.ru';

    /**
     * This email is used as site email of site administrator, users department etc
     */
    const ADMIN_EMAIL = 'rnd_tester04@cdev.ru';

    /**
     * User password
     */
    const TESTER_PASSWORD = 'master';


    static $company_email;
    static $admin_email;

    /**
    * @var XLite\Model\Profile
    */
    static $admin_profile;
    static $admin_login;

    public static function setUpBeforeClass(){
        self::$company_email = \XLite\Base::getInstance()->config->Company->users_department;
        self::$admin_email = \XLite\Base::getInstance()->config->Company->site_administrator;
        \XLite\Base::getInstance()->config->Company->users_department = self::ADMIN_EMAIL;
        \XLite\Base::getInstance()->config->Company->site_administrator = self::ADMIN_EMAIL;

        self::$admin_profile = self::getTestProfile();
        self::$admin_login = self::$admin_profile->getLogin();
        self::$admin_profile->setLogin(self::TESTER_EMAIL);

    }

    public static function tearDownAfterClass(){
        \XLite\Base::getInstance()->config->Company->users_department = self::$company_email;
        \XLite\Base::getInstance()->config->Company->site_administrator = self::$admin_email;
        self::$admin_profile->setLogin(self::$admin_login);
    }

        /**
     * Returns profile instance
     *
     * @return XLite\Model\Profile
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getTestProfile()
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(1);

        return $profile;
    }


    /**
     * testSendProfileCreatedUserNotification
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSendProfileCreatedUserNotification()
    {
        $profile = self::$admin_profile;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileCreatedUserNotification($profile);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $this->assertRegexp('/To: ' . preg_quote(self::TESTER_EMAIL) . '/msS', $email['header'], 'Wrong email recipient: ' . $email['header']);
        $this->assertRegexp('/Sign in notification/msS', $email['body'], '"Sign in notification" text not found in the email body');
    }

    /**
     * testSendProfileCreatedAdminNotification
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSendProfileCreatedAdminNotification()
    {
        $profile = self::$admin_profile;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileCreatedAdminNotification($profile);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $this->assertRegexp('/To: ' . preg_quote(self::ADMIN_EMAIL) . '/msS', $email['header'], 'Wrong email recipient' . $email['header']);
        $this->assertRegexp('/New user profile has been registered/msS', $email['body'], '"New user profile has been registered" text not found in the email body');

    }

    /**
     * testSendProfileUpdatedUserNotification
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSendProfileUpdatedUserNotification()
    {
        $profile = self::$admin_profile;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileUpdatedUserNotification($profile);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $this->assertRegexp('/To: ' . preg_quote(self::TESTER_EMAIL) . '/msS', $email['header'], 'Wrong email recipient' . $email['header']);
        $this->assertRegexp('/Your profile has been modified/msS', $email['body'], '"Your profile has been modified" text not found in the email body');
    }

    /**
     * testSendProfileUpdatedAdminNotification
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSendProfileUpdatedAdminNotification()
    {
        $profile = self::$admin_profile;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileUpdatedAdminNotification($profile);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $this->assertRegexp('/To: ' . preg_quote(self::ADMIN_EMAIL) . '/msS', $email['header'], 'Wrong email recipient' . $email['header']);
        $this->assertRegexp('/User profile modified/msS', $email['body'], '"User profile modified" text not found in the email body');

    }

    /**
     * testSendProfileDeletedAdminNotification
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSendProfileDeletedAdminNotification()
    {
        $profile = self::$admin_profile;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileDeletedAdminNotification(self::TESTER_EMAIL);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $this->assertRegexp('/To: ' . preg_quote(self::ADMIN_EMAIL) . '/msS', $email['header'], 'Wrong email recipient' . $email['header']);
        $this->assertRegexp('/User profile deleted/msS', $email['body'], '"User profile deleted" text not found in the email body');
    }

    /**
     * testSendFailedAdminLoginNotification
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSendFailedAdminLoginNotification()
    {
        $profile = self::$admin_profile;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendFailedAdminLoginNotification(self::TESTER_EMAIL);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {

            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $result = (bool)preg_match('/Administrator login failure.*' . preg_quote(self::TESTER_EMAIL) . '/msS', $email['body']);

        $this->assertTrue($result, 'Check if email contents keywords');
    }

    /**
     * testSendRecoverPasswordRequest
     * TODO: add this test
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSendRecoverPasswordRequest()
    {
        $profile = self::$admin_profile;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendRecoverPasswordRequest(self::TESTER_EMAIL, self::TESTER_PASSWORD);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {

            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $result = (bool)preg_match('/ you have requested to recover your forgotten/', $email['body']);

        $this->assertTrue($result, 'Check if email contents keywords');
    }

    /**
     * testSendRecoverPasswordConfirmation
     * TODO: add this test
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testSendRecoverPasswordConfirmation()
    {
        $profile = self::$admin_profile;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendRecoverPasswordConfirmation(self::TESTER_EMAIL, self::TESTER_PASSWORD);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {

            $this->markTestSkipped('Email notification not found in the mail box');
        }


        $email = array_shift($emails);

        $result = (bool)preg_match('/Your new password: ' . self::TESTER_PASSWORD  . '/', $email['body']);

        $this->assertTrue($result, 'Check if email contents keywords');
    }

}
