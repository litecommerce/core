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

namespace XLite\Controller\Admin;

/**
 * Profile management controller
 *
 */
class Profile extends \XLite\Controller\Admin\AAdmin
{
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
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Edit profile';
    }

    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return parent::checkACL()
            || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage users')
            || ($this->getProfile() && $this->getProfile()->getProfileId() == \XLite\Core\Auth::getInstance()->getProfile()->getProfileId());
    }

    /**
     * Check if current page is accessible
     *
     * @return boolean
     */
    public function checkAccess()
    {
        return parent::checkAccess() && $this->isOrigProfile();
    }

    /**
     * The "mode" parameter used to determine if we create new or modify existing profile
     *
     * @return boolean
     */
    public function isRegisterMode()
    {
        return self::getRegisterMode() === \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Profile
     */
    protected function getProfile()
    {
        return $this->getModelForm()->getModelObject() ?: new \XLite\Model\Profile();
    }


    /**
     * Return true if profile is not related with any order (i.e. it's an original profile)
     *
     * @return boolean
     */
    protected function isOrigProfile()
    {
        return !($this->getProfile()->getOrder());
    }

    /**
     * Class name for the \XLite\View\Model\ form
     *
     * @return string
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Profile\AdminMain';
    }

    /**
     * Modify profile action
     *
     * @return void
     */
    protected function doActionModify()
    {
        $this->getModelForm()->performAction('modify');
    }

    /**
     * actionPostprocessModify
     *
     * @return void
     */
    protected function actionPostprocessModify()
    {
        $params = array();

        if ($this->getModelForm()->isRegisterMode()) {

            // New profile is registered
            if ($this->isActionError()) {

                // Return back to register page
                $params = array('mode' => self::getRegisterMode());

            } else {

                // Send notification to the user
                \XLite\Core\Mailer::sendProfileCreatedUserNotification($this->getProfile());

                // Send notification to the users department
                \XLite\Core\Mailer::sendProfileCreatedAdminNotification($this->getProfile());

                // Return to the created profile page
                $params = array('profile_id' => $this->getProfile()->getProfileId());
            }

        } else {
            // Existsing profile is updated

            // Send notification to the user
            \XLite\Core\Mailer::sendProfileUpdatedUserNotification($this->getProfile());

            // Send notification to the users department
            \XLite\Core\Mailer::sendProfileUpdatedAdminNotification($this->getProfile());

            // Get profile ID from modified profile model
            $profileId = $this->getProfile()->getProfileId();

            // Return to the profile page
            $params = array('profile_id' => $profileId);

        }

        if (!empty($params)) {
            $this->setReturnURL($this->buildURL('profile', '', $params));
        }
    }

    /**
     * Delete profile action
     *
     * @return void
     */
    protected function doActionDelete()
    {
        $result = $this->getModelForm()->performAction('delete');

        // Send notification to the user
        \XLite\Core\Mailer::sendProfileDeletedAdminNotification($this->getProfile()->getLogin());

        $this->setReturnURL($this->buildURL('profile_list'));
    }
}
