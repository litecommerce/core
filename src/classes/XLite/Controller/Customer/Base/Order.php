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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Customer\Base;

/**
 * Order
 *
 */
abstract class Order extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Order (cache)
     *
     * @var \XLite\Model\Order
     */
    protected $order;

    /**
     * Return current order ID
     *
     * @return integer
     */
    protected function getOrderId()
    {
        return intval(\XLite\Core\Request::getInstance()->order_id);
    }

    /**
     * Return current order
     *
     * @return \XLite\Model\Order
     */
    protected function getOrder()
    {
        if (!isset($this->order)) {
            $this->order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($this->getOrderId());
        }

        return $this->order;
    }

    /**
     * Check if currently logged user is an admin
     *
     * @return boolean
     */
    protected function isAdmin()
    {
        return \XLite\Core\Auth::getInstance()->isAdmin();
    }

    /**
     * Check if order corresponds to current user
     *
     * @return boolean
     */
    protected function checkOrderProfile()
    {
        return \XLite\Core\Auth::getInstance()->getProfile()->getProfileId()
            == $this->getOrder()->getOrigProfile()->getProfileId();
    }

    /**
     * Check order access
     *
     * @return boolean
     */
    protected function checkOrderAccess()
    {
        return \XLite\Core\Auth::getInstance()->isLogged() && ($this->isAdmin() || $this->checkOrderProfile());
    }

    /**
     * Check if current page is accessible
     *
     * @return boolean
     */
    protected function checkAccess()
    {
        return parent::checkAccess() && $this->getOrder() && $this->checkOrderAccess();
    }

    /**
     * Add the base part of the location path
     *
     * @return void
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Search for orders', $this->buildURL('order_list'));
    }
}
