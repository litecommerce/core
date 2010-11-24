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
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Profile management controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Profile extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->getModelForm()->getModelObject()->getLogin();
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Search profiles', $this->buildURL('users'));
    }

    /**
     * Class name for the \XLite\View\Model\ form
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Profile\AdminMain';
    }

    /**
     * Modify profile action
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function doActionModify()
    {
        $this->getModelForm()->performAction('modify');
    }

    /**
     * actionPostprocessModify 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
                \XLite\Core\Mailer::sendProfileCreatedUserNotification($this->getModelForm()->getModelObject());

                // Send notification to the users department
                \XLite\Core\Mailer::sendProfileCreatedAdminNotification($this->getModelForm()->getModelObject());

                // Return to the created profile page
                $params = array('profile_id' => $this->getModelForm()->getModelObject()->getProfileId());
            }

        } else {

            // Existsing profile is updated

            // Send notification to the user
            \XLite\Core\Mailer::sendProfileUpdatedUserNotification($this->getModelForm()->getModelObject());

            // Send notification to the users department
            \XLite\Core\Mailer::sendProfileUpdatedAdminNotification($this->getModelForm()->getModelObject());

            // Get profile ID from modified profile model
            $profileId = $this->getModelForm()->getModelObject()->getProfileId();

            // Return to the profile page
            $params = array('profile_id' => $profileId);

        }

        if (!empty($params)) {
            $this->setReturnUrl($this->buildURL('profile', '', $params));
        }
    }

    /**
     * Delete profile action
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        $userLogin = $this->getModelForm()->getModelObject()->getLogin();

        $result = $this->getModelForm()->performAction('delete');

        // Send notification to the user
        \XLite\Core\Mailer::sendProfileDeletedAdminNotification($userLogin);

        $this->setReturnUrl($this->buildURL('users', '', array('mode' => 'search')));
    }

    /**
     * Return value for the "register" mode param
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getRegisterMode()
    {
        return 'register';
    }

    /**
     * The "mode" parameter used to determine if we create new or modify existing profile
     *
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isRegisterMode()
    {
        return self::getRegisterMode() === \XLite\Core\Request::getInstance()->mode;
    }

}
