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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\DrupalConnector\Controller\Customer;

/**
 * Abstract controller (customer interface)
 *
 */
class ACustomer extends \XLite\Controller\Customer\ACustomer implements \XLite\Base\IDecorator
{
    /**
     * Die if trying to access storefront and DrupalConnector module is enabled
     *
     * @return void
     */
    protected function checkStorefrontAccessability()
    {
        // Run parent method to make some "parent" changes.
        parent::checkStorefrontAccessability();

        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS();
    }

    /**
     * Return Drupal URL
     *
     * @return string|void
     */
    protected function getDrupalLink()
    {
        return \XLite\Core\Config::getInstance()->CDev->DrupalConnector->drupal_root_url
            ?
            : \XLite\Core\Converter::buildURL(null, null, array(), \XLite::CART_SELF);
    }

    /**
     * Perform some actions to prohibit access to storefornt
     *
     * @return void
     */
    protected function closeStorefront()
    {
        $this->getDrupalLink() ? \XLite\Core\Operator::redirect($this->getDrupalLink()) : parent::closeStorefront();
    }
}
