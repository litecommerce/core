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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Controller\Customer;

/**
 * Checkout success page
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class CheckoutSuccess extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Controller parameters
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $params = array('target', 'order_id');

    /**
     * Order (cache)
     * 
     * @var   \XLite\Model\Order
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $order;


    /**
     * Get page title
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest()
    {
        // security check on return page
        $orderId = \XLite\Core\Request::getInstance()->order_id;
        if (
            $orderId != $this->session->get('last_order_id')
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
     * @see    ____func_see____
     * @since  3.0.0
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
     * @see    ____func_see____
     * @since  3.0.0
     */     
    protected function getLocation()
    {
        return 'Checkout';
    }
}
