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
 * Icons widget in header
 *
 * @ListChild (list="layout.header.bar.links.newby", zone="customer", weight="101")
 */
class Icons extends \XLite\View\AView
{
    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/CDev/SocialLogin/header.css';

        return $list;
    }

    /**
     * Returns an array of icons of all configured auth providers
     *
     * @return array
     */
    public function getIcons()
    {
        $authProviders = \XLite\Module\CDev\SocialLogin\Core\AuthManager::getAuthProviders();

        return array_filter(
            array_map(function ($p) {
                return \XLite\Core\Layout::getInstance()->getResourceWebPath(
                    $p->getSmallIconPath(),
                    \XLite\Core\Layout::WEB_PATH_OUTPUT_URL
                );
            }, $authProviders)
        );
    }

    /**
     * Return default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/SocialLogin/small_icons.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        $authProviders = \XLite\Module\CDev\SocialLogin\Core\AuthManager::getAuthProviders();

        return parent::isVisible()
            && !\XLite\Core\Auth::getInstance()->isLogged()
            && !empty($authProviders);
    }
}
