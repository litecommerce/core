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

namespace XLite\Controller\Customer;

/**
 * User profile page controller
 *
 */
class Profile extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Types of model form
     */
    const SECTIONS_MAIN      = 'main';
    const SECTIONS_ADDRESSES = 'addresses';
    const SECTIONS_ALL       = 'all';

    /**
     * Return value for the "register" mode param
     *
     * @return string
     */
    public static function getRegisterMode()
    {
        return 'register';
    }

    /**
     * handleRequest 
     * 
     * @return void
     */
    public function handleRequest()
    {
        if (!$this->isLogged() && !$this->isRegisterMode()) {
            $this->setReturnURL($this->buildURL('login'));
        }

        return parent::handleRequest();
    }

    /**
     * Returns title of the page
     * 
     * @return void
     */
    public function getTitle()
    {
        return 'delete' == \XLite\Core\Request::getInstance()->mode 
            ? 'Delete account' 
            : parent::getTitle();
    }

    /**
     * The "mode" parameter used to determine if we create new or modify existing profile
     *
     * @return boolean
     */
    public function isRegisterMode()
    {
        return self::getRegisterMode() === \XLite\Core\Request::getInstance()->mode
            || !$this->getModelForm()->getModelObject()->isPersistent();
    }

    /**
     * Define current location for breadcrumbs
     *
     * @return string
     */
    protected function getLocation()
    {
        return 'Account details';
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('My account');
    }

    /**
     * Return class name of the register form
     *
     * @return string|void
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Profile\Main';
    }

    /**
     * Check if profile is not exists
     *
     * @return boolean
     */
    protected function doActionValidate()
    {
        return $this->getModelForm()->performAction('validateInput');
    }

    /**
     * doActionRegister
     *
     * @return boolean
     */
    protected function doActionRegister()
    {
        $result = $this->getModelForm()->performAction('create');

        // Return to the created account page or to the register page
        if ($this->isActionError()) {

            // Return back to register page
            $params = array('mode' => self::getRegisterMode());

        } else {

            // Send notification to the user
            \XLite\Core\Mailer::sendProfileCreatedUserNotification($this->getModelForm()->getModelObject());

            // Send notification to the users department
            \XLite\Core\Mailer::sendProfileCreatedAdminNotification($this->getModelForm()->getModelObject());

            $params = array('profile_id' => $this->getModelForm()->getProfileId(false));

            // Log in user with created profile
            \XLite\Core\Auth::getInstance()->loginProfile($this->getModelForm()->getModelObject());
        }

        $this->setReturnURL($this->buildURL('profile', '', $params));

        return $result;
    }

    /**
     * doActionUpdate
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        $result = $this->getModelForm()->performAction('update');

        if ($result) {

            // Send notification to the user
            \XLite\Core\Mailer::sendProfileUpdatedUserNotification($this->getModelForm()->getModelObject());

            // Send notification to the users department
            \XLite\Core\Mailer::sendProfileUpdatedAdminNotification($this->getModelForm()->getModelObject());
        }

        return $result;
    }

    /**
     * doActionModify
     *
     * @return void
     */
    protected function doActionModify()
    {
        if ($this->isRegisterMode()) {

            $this->doActionRegister();

        } else {

            $this->doActionUpdate();
        }
    }

    /**
     * doActionDelete
     *
     * @return void
     */
    protected function doActionDelete()
    {
        $userLogin = $this->getModelForm()->getModelObject()->getLogin();

        $result = $this->getModelForm()->performAction('delete');

        if ($result) {
            // Send notification to the users department
            \XLite\Core\Mailer::sendProfileDeletedAdminNotification($userLogin);
        }

        $this->setReturnURL($this->buildURL());

        return $result;
    }
}
