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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
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
     * testSendProfileCreatedUserNotification 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSendProfileCreatedUserNotification()
    {
        $profile = $this->getTestProfile();

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileCreatedUserNotification($profile);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $result = (bool)preg_match('/Sign in notification.*' . preg_quote(self::TESTER_EMAIL) . '/msS', $email['body']);
        
        $this->assertTrue($result, 'Check if email contents keywords');
    }

    /**
     * testSendProfileCreatedAdminNotification 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSendProfileCreatedAdminNotification()
    {
        $profile = $this->getTestProfile();

        \XLite\Base::getInstance()->config->Company->users_department = self::ADMIN_EMAIL;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileCreatedAdminNotification($profile);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $result = (bool)preg_match('/Sign in notification.*' . preg_quote(self::TESTER_EMAIL) . '/msS', $email['body']);

        $this->assertTrue($result, 'Check if email contents keywords');
    }

    /**
     * testSendProfileUpdatedUserNotification 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSendProfileUpdatedUserNotification()
    {
        $profile = $this->getTestProfile();

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileUpdatedUserNotification($profile);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $result = (bool)preg_match('/Profile modified.*' . preg_quote(self::TESTER_EMAIL) . '/msS', $email['body']);
        
        $this->assertTrue($result, 'Check if email contents keywords');
    }

    /**
     * testSendProfileUpdatedAdminNotification 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSendProfileUpdatedAdminNotification()
    {
        $profile = $this->getTestProfile();

        \XLite\Base::getInstance()->config->Company->users_department = self::ADMIN_EMAIL;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileUpdatedAdminNotification($profile);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $result = (bool)preg_match('/Profile modified.*' . preg_quote(self::TESTER_EMAIL) . '/msS', $email['body']);

        $this->assertTrue($result, 'Check if email contents keywords');
    }

    /**
     * testSendProfileDeletedAdminNotification 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSendProfileDeletedAdminNotification()
    {
        $profile = $this->getTestProfile();

        \XLite\Base::getInstance()->config->Company->users_department = self::ADMIN_EMAIL;

        $this->startCheckingMail();

        \XLite\Core\Mailer::sendProfileDeletedAdminNotification(self::TESTER_EMAIL);

        sleep(3);

        $emails = $this->finishCheckingMail();

        if (empty($emails)) {
            $this->markTestSkipped('Email notification not found in the mail box');
        }

        $email = array_shift($emails);

        $result = (bool)preg_match('/Profile deleted.*' . preg_quote(self::TESTER_EMAIL) . '/msS', $email['body']);

        $this->assertTrue($result, 'Check if email contents keywords');
    }

    /**
     * testSendFailedAdminLoginNotification 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSendFailedAdminLoginNotification()
    {
        $profile = $this->getTestProfile();

        \XLite\Base::getInstance()->config->Company->site_administrator = self::ADMIN_EMAIL;

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
     * @since  3.0.0
     */
    public function testSendRecoverPasswordRequest()
    {
        $this->markTestIncomplete();
    }

    /**
     * testSendRecoverPasswordConfirmation 
     * TODO: add this test
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testSendRecoverPasswordConfirmation()
    {
        $this->markTestIncomplete();
    }

    /**
     * Returns profile instance
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTestProfile()
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find(1);

        $profile->setLogin(self::TESTER_EMAIL);

        return $profile;
    }

}
