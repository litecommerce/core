<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\View\Checkout;

/**
 * Profile widget on Checkout page
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Profile extends \XLite\View\Checkout\Profile implements \XLite\Base\IDecorator
{
    /**
     * Get cart Drupal's user name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getUsername()
    {
        return \XLite\Core\Session::getInstance()->order_username ?: '';
    }

    /**
     * Get current profile username
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProfileUsername()
    {
        $profile = $this->getCart()->getProfile()->getCMSProfile();

        return $profile ? $profile->name : parent::getProfileUsername();
    }

    /**
     * Get profile page URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProfileURL()
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            ? url('user')
            : parent::getProfileURL();
    }

    /**
     * Get log-off page URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getLogoffURL()
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            ? url('user/logout')
            : parent::getLogoffURL();
    }
}
