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
class XLite_Module_Promotion_Controller_Admin_DiscountCoupon extends XLite_Controller_Admin_Abstract
{
    public $params = array('target', "coupon_id");

    function getDC() 
    {
        if (is_null($this->_dc)) {
            $this->_dc = new XLite_Module_Promotion_Model_DiscountCoupon($this->get('coupon_id'));
        }
        return $this->_dc;
    }

    function action_update() 
    {
        $dc = new XLite_Module_Promotion_Model_DiscountCoupon();
        if ($dc->find("coupon='" . addslashes($_POST['coupon']) . "' AND order_id='0' AND coupon_id<>'".addslashes($this->get('coupon_id'))."'")) {
            $this->set('valid', false);
            $this->couponCodeDuplicate = true;
            return;
        }

        if ($dc->find("coupon_id='".addslashes($this->get('coupon_id'))."'")) {
            $_POST['discount'] = abs($_POST['discount']);
            $_POST['expire'] = $this->get('expire');
            $dc->set('properties', $_POST);
            $dc->update();
            $this->set('returnUrl', $this->get('url')."&couponUpdated=1");
        }
    }
}
