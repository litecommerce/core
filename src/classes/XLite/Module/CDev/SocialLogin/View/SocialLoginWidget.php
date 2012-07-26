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
 * @since     1.0.24
 */

namespace XLite\Module\CDev\SocialLogin\View;

/**
 * Social sign-in widget
 *
 * @see   ____class_see____
 * @since 1.0.24
 */
class SocialLoginWidget extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_CAPTION     = 'caption';
    const PARAM_TEXT_BEFORE = 'text_before';
    const PARAM_TEXT_AFTER  = 'text_after';

    /**
     * Get all configured authentication providers
     * 
     * @return array Auth providers list
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getAuthProviders()
    {
        return \XLite\Module\CDev\SocialLogin\Core\AuthManager::getAuthProviders();
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        // TODO: Remove after CSS-autoloading is added.
        $list[] = 'modules/CDev/SocialLogin/style.css';

        return $list;
    }

    /**
     * Return default template
     * See setWidgetParams()
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/SocialLogin/social_login.tpl';
    }

    /**  
     * Check if widget is visible
     * (there should be at least one active auth provider)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {    
        return parent::isVisible()
            && $this->getAuthProviders()
            && !\XLite\Core\Auth::getInstance()->isLogged();
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_CAPTION       => new \XLite\Model\WidgetParam\String('Caption', null),
            static::PARAM_TEXT_BEFORE   => new \XLite\Model\WidgetParam\String('TextBefore', null),
            static::PARAM_TEXT_AFTER    => new \XLite\Model\WidgetParam\String('TextAfter', null),
        );
    }

    /**
     * Get widget caption
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getCaption()
    {
        return $this->getParam(static::PARAM_CAPTION);
    }

    /**
     * Get widget's preceding text
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getTextBefore()
    {
        return $this->getParam(static::PARAM_TEXT_BEFORE);
    }

    /**
     * Get widget's following text
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getTextAfter()
    {
        return $this->getParam(static::PARAM_TEXT_AFTER);
    }
}
