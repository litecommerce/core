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
 * Cart controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_UPSOnlineTools_Controller_Customer_Cart extends XLite_Controller_Customer_Cart
implements XLite_Base_IDecorator
{
    /**
     * Update cart
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $carrier = $this->getCart()->getCarrier();

        if (
            0 < count($this->getCart()->getCarriers())  
            && isset(XLite_Core_Request::getInstance()->carrier)
            && $carrier
            && XLite_Core_Request::getInstance()->carrier != $carrier
        ) {

            // Update carrier
            $rates = $this->getCart()->getCarrierRates(XLite_Core_Request::getInstance()->carrier);
            if (!$rates) { 
                XLite_Core_Request::getInstance()->shipping = 0;

            } elseif (!isset($rates[$this->getCart()->get('shipping_id')])) {
                $rate = array_shift($rates);
                $shipping = $rate->get('shipping');
                XLite_Core_Request::getInstance()->shipping = $shipping ? $shipping->get('shipping_id') : 0;

            }
        }

        parent::doActionUpdate();
    }
}
