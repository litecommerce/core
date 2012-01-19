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

namespace XLite\View\Model\Profile;

/**
 * Profile model widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AProfile extends \XLite\View\Model\AModel
{
    /**
     * Return model object to use
     *
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModelObject()
    {
        $profile = parent::getModelObject();

        // Reset profile if it's not valid
        if (!\XLite\Core\Auth::getInstance()->checkProfile($profile)) {
            $profile = \XLite\Model\CachingFactory::getObject(__METHOD__, '\XLite\Model\Profile');
        }

        return $profile;
    }

    /**
     * getRequestProfileId
     *
     * @return integer|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRequestProfileId()
    {
        return \XLite\Core\Request::getInstance()->profile_id;
    }

    /**
     * Return current profile ID
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProfileId()
    {
        return $this->getRequestProfileId() ?: \XLite\Core\Session::getInstance()->profile_id;
    }


    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultModelObject()
    {
        $obj = \XLite\Core\Database::getRepo('XLite\Model\Profile')->find($this->getProfileId());

        if (!isset($obj)) {
            $obj = new \XLite\Model\Profile();
        }

        return $obj;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Profile';
    }

    /**
     * Return text for the "Submit" button
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSubmitButtonLabel()
    {
        return \XLite\Core\Auth::getInstance()->isLogged() ? static::t('Update profile') : static::t('Create new account');
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();
        $result['submit'] = new \XLite\View\Button\Submit(
            array(
                \XLite\View\Button\AButton::PARAM_LABEL => $this->getSubmitButtonLabel(),
                \XLite\View\Button\AButton::PARAM_STYLE => 'action',
            )
        );

        return $result;
    }
}
