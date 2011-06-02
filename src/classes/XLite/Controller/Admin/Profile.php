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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Profile management controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Profile extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return value for the "register" mode param
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getRegisterMode()
    {
        return 'register';
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Edit profile';
    }

    /**
     * The "mode" parameter used to determine if we create new or modify existing profile
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isRegisterMode()
    {
        return self::getRegisterMode() === \XLite\Core\Request::getInstance()->mode;
    }


    /**
     * Alias
     *
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProfile()
    {
        return $this->getModelForm()->getModelObject() ?: new \XLite\Model\Profile();
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return $this->getProfile()->getLogin();
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Users', $this->buildURL('users'));
    }

    /**
     * Class name for the \XLite\View\Model\ form
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Profile\AdminMain';
    }

    /**
     * Modify profile action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionModify()
    {
        $this->getModelForm()->performAction('modify');
    }

    /**
     * actionPostprocessModify
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDelete()
    {
        $userLogin = $this->getProfile()->getLogin();

        $result = $this->getModelForm()->performAction('delete');

        // Send notification to the user
        \XLite\Core\Mailer::sendProfileDeletedAdminNotification($userLogin);

        $this->setReturnURL($this->buildURL('users', '', array('mode' => 'search')));
    }
}
