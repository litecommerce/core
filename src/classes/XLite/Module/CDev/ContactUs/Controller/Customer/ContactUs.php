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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\ContactUs\Controller\Customer;

/**
 * Contact us controller
 *
 * @see   ____class_see____
 * @since 1.0.18
 */
class ContactUs extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Default title
     */
    const DEFAULT_TITLE = 'Contact us';

    /**
     * Fields
     *
     * @var   array
     */
    protected $requiredFields = array(
        'name'    => 'Your name',
        'email'   => 'Your e-mail',
        'subject' => 'Subject',
        'message' => 'Message'
    );

    /**
     * Check if current page is accessible
     *
     * @return boolean
     */
    public function checkAccess()
    {
        return parent::checkAccess()
            && \XLite\Core\Config::getInstance()->CDev->ContactUs->enable_form;
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return \XLite\Core\Config::getInstance()->CDev->ContactUs->page_title ?: self::DEFAULT_TITLE;
    }

    /**
     * Return value of data
     *
     * @param string $field Field
     *
     * @return string
     */
    public function getValue($field)
    {
        $data = \XLite\Core\Session::getInstance()->contact_us;

        $value = $data && isset($data[$field]) ? $data[$field] : '';

        if (
            !$value
            && in_array($field, array('name', 'email'))
        ) {
            $auth = \XLite\Core\Auth::getInstance();
            if ($auth->isLogged()) {
                if ('email' == $field) {
                    $value = $auth->getProfile()->getLogin();
                } elseif (0 < $auth->getProfile()->getAddresses()->count()) {
                    $value = $auth->getProfile()->getAddresses()->first()->getName();
                }
            }
        }

        return $value;
    }

    /**
     * Common method to determine current location
     *
     * @return string
     */
    protected function getLocation()
    {
        return \XLite\Core\Config::getInstance()->CDev->ContactUs->page_title ?: self::DEFAULT_TITLE;
    }

    /**
     * Send message
     *
     * @return void
     */
    protected function doActionSend()
    {
        $data = \XLite\Core\Request::getInstance()->getData();
        $config = \XLite\Core\Config::getInstance()->CDev->ContactUs;
        $isValid = true;

        foreach ($this->requiredFields as $key => $name) {
            if (
                !isset($data[$key])
                || empty($data[$key])
            ) {
                $isValid = false;
                \XLite\Core\TopMessage::addError(
                    \XLite\Core\Translation::lbl(
                        'The X field is empty', array('name' => $name)
                    )
                );
            }
        }

        if (
            $isValid
            && false === filter_var($data['email'], FILTER_VALIDATE_EMAIL)
        ) {

            $isValid = false;
            \XLite\Core\TopMessage::addError(
                \XLite\Core\Translation::lbl(
                    'The value of the X field has an incorrect format',
                    array('name' => $this->requiredFields['email'])
                )
            );
        }

        if (
            $isValid
            && $config->recaptcha_private_key
            && $config->recaptcha_public_key
        ) {
            require_once LC_DIR_MODULES . '/CDev/ContactUs/recaptcha/recaptchalib.php';
            $resp = recaptcha_check_answer(
                $config->recaptcha_private_key,
                $_SERVER['REMOTE_ADDR'],
                $data['recaptcha_challenge_field'],
                $data['recaptcha_response_field']
            );

            $isValid = $resp->is_valid;

            if (!$isValid) {
                \XLite\Core\TopMessage::addError('Please enter the correct captcha');
            }
        }

        if ($isValid) {
            \XLite\Core\Mailer::getInstance()->sendContactUsMessage(
                $data,
                \XLite\Core\Config::getInstance()->CDev->ContactUs->email
                ?: \XLite\Core\Config::getInstance()->Company->site_administrator
            );
            unset($data['message']);
            unset($data['subject']);
            \XLite\Core\TopMessage::addInfo('Message has been sent');
        }

        \XLite\Core\Session::getInstance()->contact_us = $data;
    }

}
