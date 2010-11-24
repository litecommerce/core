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

namespace XLite\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * Return class name of the register form 
     * 
     * @return string|void
     * @access protected
     * @since  3.0.0
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\Profile\Main';
    }

    /**
     * Check if profile is not exists
     * 
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionValidate()
    {
        return $this->getModelForm()->performAction('validateInput');
    }

    /**
     * doActionRegister 
     * 
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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

            // Send notification to the user
            $params = array('profile_id' => $this->getModelForm()->getProfileId(false));
        }

        $this->setReturnUrl($this->buildURL('profile', '', $params));

        return $result;
    }

    /**
     * doActionUpdate
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * doActionDelete 
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
    
        if ($result) {
            // Send notification to the users department
            \XLite\Core\Mailer::sendProfileDeletedAdminNotification($userLogin);
        }

        return $result;
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
}
