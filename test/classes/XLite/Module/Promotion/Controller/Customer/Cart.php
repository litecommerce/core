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
class XLite_Module_Promotion_Controller_Customer_Cart extends XLite_Controller_Customer_Cart implements XLite_Base_IDecorator
{
    public $discountCouponResult = false;

    function handleRequest()
    {
        $discountCoupon = $this->cart->get("DC");
        if ($discountCoupon)
            if (!$discountCoupon->checkCondition($this->cart)) {
                $dc = $this->cart->get("DC");
            	$this->session->set("couponFailed", $dc->get("coupon"));
                $this->cart->set("DC", null); // remove coupon
                $this->updateCart();
                $this->redirect("cart.php?target=checkout&mode=couponFailed");
                return;
            }
        
        if ($this->get("target") == 'cart') {
            $this->session->set("bonusListDisplayed", null);
        }
        parent::handleRequest();
    }
    
    function action_discount_coupon_delete()
    {
        $this->cart->set("DC", null);
        $this->updateCart();
    }
    
    function action_discount_coupon()
    {
        $this->coupon = addSlashes(trim($this->coupon));
        $this->discountCouponResult = $this->cart->validateDiscountCoupon($this->coupon);
        $dc = new XLite_Module_Promotion_Model_DiscountCoupon();
        $found = $dc->find("coupon='".$this->coupon."' AND order_id='0'");
        if ($this->discountCouponResult||!$dc->checkCondition($this->cart)) {
            $this->valid = false;
            // show error message
            $this->session->set("couponFailed", $dc->get("coupon"));
            $this->redirect("cart.php?target=checkout&mode=couponFailed");
            return;
        }

        if($found) {
            $this->cart->set("DC", $dc);
            $this->updateCart();
        } else {
            $this->doDie("Internal error: DC not found");
        }
    }
    
    function isShowDCForm()
    {
        return is_null($this->cart->get("DC")) && !$this->cart->is("empty") && $this->config->getComplex('Promotion.allowDC');
    }

}
