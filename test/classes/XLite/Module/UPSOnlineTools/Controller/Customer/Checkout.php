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
 * Checkout controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_UPSOnlineTools_Controller_Customer_Checkout extends XLite_Controller_Customer_Checkout
implements XLite_Base_IDecorator
{
    /**
     * Change shipping method
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionShipping()
    {
        $carrier = $this->getCart()->getCarrier();

        if (
            0 < count($this->getCart()->getCarriers())  
            && $carrier
            && isset(XLite_Core_Request::getInstance()->carrier)
            && XLite_Core_Request::getInstance()->carrier != $carrier
        ) {

            // Update carrier
            $newrates = $this->getCart()->getCarrierRates(XLite_Core_Request::getInstance()->carrier);

            $shipping = null;
            if (count($newrates) > 0) {
                $newShippingRate = array_shift($newrates);
                $shipping = $newShippingRate->getComplex('shipping.shipping_id');
            }
            
            XLite_Core_Request::getInstance()->shipping = $shipping;

            $this->set(
                'returnUrl',
                $this->buildUrl('checkout', '', array('mode' => 'paymentMethod'))
            );
        }

        parent::doActionShipping();
    }
}
