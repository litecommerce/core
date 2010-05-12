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
 * @subpackage Model
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
class XLite_Model_PaymentMethod_Echeck extends XLite_Model_PaymentMethod
{
    public $formTemplate = "checkout/echeck.tpl";
    public $secure = true;

    function process($cart)
    {
        $cart->set("detailLabels", array(
                    "ch_routing_number" => "ABA routing number",
                    "ch_acct_number" => "Bank Account Number",
                    "ch_type" => "Type of Account",
                    "ch_bank_name" => "Bank name",
                    "ch_acct_name" => "Account name",
                    "ch_number" => "Check number"));
        $cart->set("status", "Q");
        $cart->update();
    }

    /**
     * Handle request 
     * 
     * @param XLite_Model_Cart $cart Cart
     *  
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest(XLite_Model_Cart $cart)
    {
        $this->process($cart);
        $status = $cart->get("status");

        return ($status == 'Q' || $status == 'P') ? self::PAYMENT_SUCCESS : self::PAYMENT_FAILURE;
    }
}
