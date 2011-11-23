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

namespace XLite\Module\CDev\DrupalConnector\Controller\Customer;

/**
 * Abstract controller (customer interface)
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ACustomer extends \XLite\Controller\Customer\ACustomer implements \XLite\Base\IDecorator
{
    /**
     * Die if trying to access storefront and DrupalConnector module is enabled
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDrupalLink()
    {
        return \XLite\Core\Config::getInstance()->CDev->DrupalConnector->drupal_root_url;
    }

    /**
     * Perform some actions to prohibit access to storefornt
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function closeStorefront()
    {
        $this->getDrupalLink() ? \XLite\Core\Operator::redirect($this->getDrupalLink()) : parent::closeStorefront();
    }
}
