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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_CheckoutSuccess extends XLite_Controller_Customer_Abstract
{
    public $params = array("target", "order_id");
    public $order = null;


    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */     
    protected function getLocation()
    {
        return 'Checkout';
    }


    /**
     * Get page title
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Thank you for your order';
    }



    function handleRequest()
    {
        // security check on return page
        $order_id = $this->get('order_id');
        if ($order_id != $this->session->get('last_order_id') &&
                $order_id != $this->getCart()->get('order_id')) {
            $this->redirect("cart.php?mode=accessDenied");
        } else {
            parent::handleRequest();
        }
    }

    function getOrder()
    {
        if (!isset($this->order)) {
            $this->order = new XLite_Model_Order($this->get('order_id'));
        }
        return $this->order;
    }

    function getCharset()
    {
        $charset = $this->getComplex('order.profile.billingCountry.charset');
        return ($charset) ? $charset : parent::getCharset();
    }

    function getSecure()
    {
        return $this->getComplex('config.Security.customer_security');
    }
}
