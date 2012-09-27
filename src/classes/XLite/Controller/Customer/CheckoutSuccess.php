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

namespace XLite\Controller\Customer;

/**
 * Checkout success page
 *
 */
class CheckoutSuccess extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Controller parameters
     *
     * @var array
     */
    protected $params = array('target', 'order_id');

    /**
     * Order (cache)
     *
     * @var \XLite\Model\Order
     */
    protected $order;


    /**
     * Get page title
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Thank you for your order';
    }

    /**
     * Handles the request.
     * Parses the request variables if necessary. Attempts to call the specified action function
     *
     * @return void
     */
    public function handleRequest()
    {
        \XLite\Core\Session::getInstance()->iframePaymentData = null;

        // security check on return page
        $orderId = \XLite\Core\Request::getInstance()->order_id;
        if (
            $orderId != \XLite\Core\Session::getInstance()->last_order_id
            && $orderId != $this->getCart()->getOrderId()
        ) {

            $this->redirect($this->buildURL('cart'));

        } else {

            parent::handleRequest();
        }
    }

    /**
     * Get order
     *
     * @return \XLite\Model\Order
     */
    public function getOrder()
    {
        if (!isset($this->order)) {

            $this->order = \XLite\Core\Database::getRepo('XLite\Model\Order')
                ->find(\XLite\Core\Request::getInstance()->order_id);
        }

        return $this->order;
    }


    /**
     * Common method to determine current location
     *
     * @return string
     */
    protected function getLocation()
    {
        return 'Checkout';
    }
}
