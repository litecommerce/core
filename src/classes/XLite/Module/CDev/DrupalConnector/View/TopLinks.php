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

namespace XLite\Module\CDev\DrupalConnector\View;

/**
 * 'Powered by' widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class TopLinks extends \XLite\View\TopLinks implements \XLite\Base\IDecorator
{
    /**
     * Gathering Drupal return URL from request and save it in session
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function init()
    {
        parent::init();

        $paramReturnURL = \XLite\Module\CDev\DrupalConnector\Drupal\Module::PARAM_DRUPAL_RETURN_URL;

        // User come from Drupal - save return URL in session
        if (\XLite\Core\Request::getInstance()->$paramReturnURL) {
            \XLite\Core\Session::getInstance()->$paramReturnURL = \XLite\Core\Request::getInstance()->$paramReturnURL;
        }
    }


    /**
     * Disable storefront menu in top links
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isStorefrontMenuVisible()
    {
        return false;
    }

    /**
     * Return Drupal frontend URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDrupalURL()
    {
        return \XLite\Core\Config::getInstance()->CDev->DrupalConnector->drupal_root_url;
    }

    /**
     * Check if Drupal URL is stored in config variables
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function hasDrupalURL()
    {
        return isset(\XLite\Core\Config::getInstance()->CDev->DrupalConnector->drupal_root_url)
            && !empty(\XLite\Core\Config::getInstance()->CDev->DrupalConnector->drupal_root_url);
    }

    /**
     * Returns a Drupal return URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDrupalReturnURL()
    {
        return \XLite\Core\Session::getInstance()
            ->{\XLite\Module\CDev\DrupalConnector\Drupal\Module::PARAM_DRUPAL_RETURN_URL};
    }

    /**
     * Check if Drupal return URL is saved in session
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function hasDrupalReturnURL()
    {
        $paramReturnURL = \XLite\Module\CDev\DrupalConnector\Drupal\Module::PARAM_DRUPAL_RETURN_URL;

        return isset(\XLite\Core\Session::getInstance()->$paramReturnURL)
            && !empty(\XLite\Core\Session::getInstance()->$paramReturnURL)
            && !($this->hasDrupalURL() && \XLite\Core\Session::getInstance()->$paramReturnURL == $this->getDrupalURL());
    }

    /**
     * check if Drupal menu is visible in top links
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isDrupalStorefrontLinkVisible()
    {
        return $this->hasDrupalURL() || $this->hasDrupalReturnURL();
    }
}
