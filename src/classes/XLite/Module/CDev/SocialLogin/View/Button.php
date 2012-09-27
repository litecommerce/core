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
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\SocialLogin\View;

/**
 * Abstract sign-in button
 *
 */
abstract class Button extends \XLite\View\AView
{
    /**
     * Widget icon path
     */
    const ICON_PATH = 'modules/CDev/SocialLogin/icons/default.png';

    /**
     * Returns an instance of auth provider
     *
     * @return \XLite\Module\CDev\SocialLogin\Core\AAuthProvider
     */
    abstract protected function getAuthProvider();

    /**
     * Get widget display name
     *
     * @return string
     */
    public function getName()
    {
        return static::DISPLAY_NAME;
    }

    /**
     * Get path to auth provider icon
     *
     * @return string
     */
    public function getIconPath()
    {
        return static::ICON_PATH;
    }

    /**
     * Get web path to a provider's icon
     *
     * @param string $iconPath Icon path relative to skins directory
     *
     * @return string Icon web path
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
     */
    public function getAuthRequestUrl()
    {
        $state = get_class(\XLite::getController());

        return $this->getAuthProvider()->getAuthRequestUrl($state);
    }

    /**
     * Return default template
     *
     * @return string
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
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getAuthProvider()->isConfigured();
    }
}
