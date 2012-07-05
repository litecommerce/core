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
 * @since     1.0.18
 */

namespace XLite\Module\CDev\SocialLogin\View;

/**
 * Abstract sign-in button
 * 
 * @see   ____class_see____
 * @since 1.0.24
 */
abstract class Button extends \XLite\View\AView
{

    /**
     * Get auth provider name to display in customer area widgets
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    abstract public function getName();

    /**
     * Returns an instance of auth provider
     * 
     * @return \XLite\Module\CDev\SocialLogin\Core\AAuthProvider
     * @see    ____func_see____
     * @since  1.0.24
     */
    abstract protected function getAuthProvider();

    /**
     * Get path to auth provider icon
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getIconPath()
    {
        return 'modules/CDev/SocialLogin/icons/default.png';
    }

    /**
     * Get web path to a provider's icon
     * 
     * @param string $iconPath Icon path relative to skins directory
     *  
     * @return string Icon web path
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getIconWebPath($iconPath)
    {
        return \XLite\Core\Layout::getInstance()->getResourceWebPath(
            $iconPath,
            \XLite\Core\Layout::WEB_PATH_OUTPUT_URL
        );
    }

    /**
     * Get authentication request url
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getAuthRequestUrl()
    {
        return $this->getAuthProvider()->getAuthRequestUrl();
    }

    /**
     * Return default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/SocialLogin/button.tpl';
    }

    /**  
     * Check if widget is visible
     * (auth provider must be fully configured)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {    
        return parent::isVisible() && $this->getAuthProvider()->isConfigured();
    }
}
