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
class XLite_Controller_Admin_ShippingMethods extends XLite_Controller_Admin_ShippingSettings
{
    function getPageTemplate()
    {
        return "shipping/methods.tpl";
    }

    function action_add()
    {
        $shipping = new XLite_Model_Shipping();
        $shipping->set('properties', XLite_Core_Request::getInstance()->getData());
        $shipping->create();
        $this->xlite->set('action_add_valid', true);
    }

    function action_update()
    {
        foreach (XLite_Core_Request::getInstance()->order_by as $shipping_id=>$order_by) {
            $shipping = new XLite_Model_Shipping($shipping_id);
            $shipping->set('order_by', $order_by);
            $enabled = 0;

            if (isset(XLite_Core_Request::getInstance()->enabled)) {
                $_tmp = XLite_Core_Request::getInstance()->enabled;
                if (isset($_tmp[$shipping_id])) {
                    $enabled = 1;
                }
            }
            $shipping->set('enabled', $enabled);
            $shipping->update();
        }
    }

    function action_delete()
    {
        $shipping = new XLite_Model_Shipping(XLite_Core_Request::getInstance()->shipping_id);
        $shipping->delete();
    }
}
