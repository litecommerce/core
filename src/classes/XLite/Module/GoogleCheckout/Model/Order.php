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
 * Order
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GoogleCheckout_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{
    public $GoogleCheckout_profile = null;

    public function __construct($id=null)
    {
        parent::__construct($id);
        $this->fields['google_id'] = '';
        $this->fields['google_total'] = 0;
        $this->fields['google_details'] = "";
        $this->fields['google_status'] = '';    // '_empty_' - not set, 'P' - partial refund, R - full refund, 'C' - canceled
        $this->fields['google_carrier'] = "";
    }

    function getGoogleCheckoutXML($name)
    {
        $methodName = "getGoogleCheckoutXML_" . strtoupper(substr($name, 0, 1)) . strtolower(substr($name, 1));
        if (method_exists($this, $methodName)) {
            $params = func_get_args();
            array_shift($params);
            return call_user_func_array(array($this, $methodName), $params);
        }

        return "";
    }

    function getGoogleCheckoutXML_Items()
    {
        $itemsXML = array();
        $items = $this->getItems();
        foreach ($items as $item) {
            $itemsXML[] = $item->getGoogleCheckoutXML();
        }

        $currency = $this->xlite->get('gcheckout_currency');

        // Send Global discount as order item with negative price
        if ($this->xlite->get('WholesaleTradingEnabled') && $this->get('global_discount') > 0) {
            $discountTotal = - $this->get('global_discount');
            $itemDescription = "Shopping cart global discount.";

            $itemsXML[] = <<<EOT
        <item>
            <item-name>Global discount</item-name>
            <item-description>$itemDescription</item-description>
            <unit-price currency="$currency">$discountTotal</unit-price>
            <quantity>1</quantity>
        </item>
EOT;
        }

        // Allow to use discounts as cart items
        if (!$this->xlite->get('gcheckout_remove_discounts')) {
            // Send Discount Coupon as cart item with negative price
            if ($this->xlite->get('PromotionEnabled') && $this->getDC()) {
                $coupon = $this->getDC();

                require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';

                $itemName = "Discount coupon #".$coupon->get('coupon');
                $itemDescription = GoogleCheckout_getCouponApplyDescription($coupon);
                $unitPrice = sprintf("%.02f", -doubleval($this->get('discount')));

                $itemsXML[] = <<<EOT
        <item>
            <item-name>$itemName</item-name>
            <item-description>$itemDescription</item-description>
            <unit-price currency="$currency">$unitPrice</unit-price>
            <quantity>1</quantity>
        </item>
EOT;
            }

            // Send Gift Certificate Payment as order item with negative price
            if ($this->xlite->get('GiftCertificatesEnabled') && $this->get('payedByGC') > 0) {
                $discountValue = - $this->get('payedByGC');
                $itemName = "Gift Certificate Payment";
                $itemDescription = "The order is paid for with a gift certificate partially or completely.";

                $itemsXML[] = <<<EOT
        <item>
            <item-name>$itemName</item-name>
            <item-description>$itemDescription</item-description>
            <unit-price currency="$currency">$discountValue</unit-price>
            <quantity>1</quantity>
        </item>
EOT;
            }

            // Send Payed by points Payment as order item with negative price
            if ($this->xlite->get('PromotionEnabled') && $this->get('payedByPoints') > 0) {
                $discountValue = - $this->get('payedByPoints');
                $itemName = "Bonus Points Payment";
                $itemDescription = "The order is paid for with bonus points partially or completely.";

                $itemsXML[] = <<<EOT
        <item>
            <item-name>$itemName</item-name>
            <item-description>$itemDescription</item-description>
            <unit-price currency="$currency">$discountValue</unit-price>
            <quantity>1</quantity>
        </item>
EOT;
            }
        }

        return implode("\n", $itemsXML);
    }

    function getGoogleCheckoutXML_Shippings()
    {
        $shippings = array();
        $so = new XLite_Model_Shipping();
        foreach ($so->get('modules') as $module) {
            $shipping_class = $module->get('class');

            $shipping = new XLite_Model_Shipping();
            $shippings = array_merge($shippings, $shipping->findAll("enabled=1 AND class='$shipping_class'"));
        }

        if (!is_array($shippings) || count($shippings) <= 0) {
            return "";
        }

        $shippingsXML = array();

        foreach ($shippings as $shipping) {
            $shippingRate = new XLite_Model_ShippingRate();
            $shippingRate->set('shipping', $shipping);
            $shippingsXML[] = $shippingRate->getGoogleCheckoutXML();
        }

        $shippingsXML = implode("\n", $shippingsXML);

        return <<<EOT
            <shipping-methods>
$shippingsXML
            </shipping-methods>
EOT;
    }

    function getGoogleCheckoutXML_Tax()
    {
        $subTotal = $this->calcSubTotal();
        if ($subTotal != 0) {
            $tax = $this->calcTax();
            $percent = $this->formatCurrency((($tax * 100) / $subTotal));
            $rate = $percent / 100;
        } else {
            $rate = 0;
        }

        return <<<EOT
            <tax-tables merchant-calculated="true">
                <default-tax-table>
                    <tax-rules>
                        <default-tax-rule>
                        <shipping-taxed>true</shipping-taxed>
                        <rate>$rate</rate>
                        <tax-area>
                            <us-country-area country-area="ALL"/>
                        </tax-area>
                    </default-tax-rule>
                    </tax-rules>
                </default-tax-table>
            </tax-tables>
EOT;
    }


    function getGoogleCheckoutXML_Calculation($address, $shipping, $discounts)
    {
        require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';

        return GoogleCheckout_getGoogleCheckoutXML_Calculation($this, $address, $shipping, $discounts);
    }


    function getProfile()
    {
        return is_null($this->GoogleCheckout_profile) ? parent::getProfile() : $this->GoogleCheckout_profile;
    }

    function google_checkout_setDC($dc)
    {
        if (!$this->xlite->get('PromotionEnabled'))
            return false;

        // unset existing discount coupon
        if (!is_null($this->get('DC'))) {
            $this->DC->delete();
            $this->DC = null;
        }

        if (!is_null($dc)) {
            $coupon = new XLite_Module_Promotion_Model_DiscountCoupon();
            if ( function_exists('func_is_clone_deprecated') && func_is_clone_deprecated() ) {
                $clone = $dc->cloneObject();
            } else {
                $clone = $dc->clone();
            }

            $clone->set('order_id', $this->get('order_id'));
            $clone->update();
            $this->set('discountCoupon', $dc->get('coupon_id'));
            $this->DC = $clone;

        } else {
            $this->set('discountCoupon', "");
            return false;
        }

        return true;
    }

    /**
     * Check - allow pay this order with Google checkout or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isGoogleAllowPay()
    {
        $result = true;

        foreach ($this->getItems() as $item) {
            $product = $item->getProduct();
            if ($product && $product->get('google_disabled')) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    function google_getItemsFingerprint()
    {
        if ($this->isEmpty()) {
            return false;
        }

        $result = array();
        $items = $this->get('items');
        foreach ($items as $item_idx => $item) {
            $result[] = array
            (
                $item_idx,
                $item->get('key'),
                $item->get('amount')
            );
        }

        return serialize($result);
    }

    function googleDisableNotification($status)
    {
        if ($this->get('payment_method') != "google_checkout") {
            return;
        }

        $disableCustomerNotif = $this->xlite->get('GoogleCheckoutDCN');
        if (!isset($disableCustomerNotif)) {
            $pmGC = XLite_Model_PaymentMethod::factory('google_checkout');
            $disableCustomerNotif = $pmGC->getComplex('params.disable_customer_notif');
            $this->xlite->set('GoogleCheckoutDCN', $disableCustomerNotif);
        }

        if ($disableCustomerNotif) {
            $this->xlite->set('GoogleCheckoutDCNMailer', $status);
        }
    }

    function getGoogleShippingCarrirer()
    {
        if ($this->get('google_carrier')) {
            return $this->get('google_carrier');
        }

        switch ($this->get('shippingMethod')->get('class')) {
            case 'ups':
                $result = 'UPS';
                break;

            case 'usps':
                $result = 'USPS';
                break;

            default:
                $result = 'Other';
        }

        return $result;
    }

    function getGoogleRemainRefund()
    {
        return max(0, $this->getComplex('google_details.total_charge_amount') - $this->getComplex('google_details.refund_amount'));
    }

    function getGoogleRemainCharge()
    {
        return max(0, $this->get('google_total') - $this->getComplex('google_details.total_charge_amount'));
    }

    function setGoogleDetails($value)
    {
        parent::set('google_details', serialize((array)$value));
    }

    function getGoogleDetails()
    {
        $details = parent::get('google_details');

        return $details ? unserialize($details) : array();
    }

    /**
     * Returns property value named $name. If no property found, returns null 
     * 
     * @param string $name property name
     *  
     * @return mixed
     * @access public
     * @since  3.0
     */
    public function get($name)
    {
        $result = null;

        if ($name == 'google_details') {
            $result = $this->getGoogleDetails();

        } else {
            $result = parent::get($name);
        }

        return $result;
    }

    /**
     * Set object property 
     * 
     * @param string $name  property name
     * @param mixed  $value property value
     *  
     * @return void
     * @access public
     * @since  3.0
     */
    public function set($name, $value)
    {
        if ($name == "google_details") {
            $this->setGoogleDetails($value);

        } else {
            parent::set($name, $value);
        }
    }

    function isGoogleDiscountCouponsAvailable()
    {
        if ($this->xlite->get('PromotionEnabled') && ($this->config->Promotion->allowDC)) {
            if (!is_null($this->getDC())) {
                return false;
            }

            $dc = new XLite_Module_Promotion_Model_DiscountCoupon();
            if ($dc->count("status='A' AND expire>='".time()."' AND order_id='0'") > 0) {
                return true;
            }
        }

        return false;
    }

    function isGoogleMeetDiscount()
    {
        if (!$this->xlite->get('PromotionEnabled')) {
            return true;
        }

        $dc = $this->getDC();

        return !$dc || $dc->get('applyTo') == 'total' || $dc->get('type') == 'freeship';
    }

    function isGoogleGiftCertificatesAvailable()
    {
        if ($this->xlite->get('GiftCertificatesEnabled') && is_null($this->getGC())) {
            $gc = new XLite_Module_GiftCertificates_Model_GiftCertificate();
            $certs = $gc->findAll();
            foreach ($certs as $cert) {
                if (
                    $cert->validate() == XLite_Module_GiftCertificates_Model_GiftCertificate::GC_OK
                    && 0 < $cert->get('debit')
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    function isShowGoogleCheckoutNotes()
    {
        if ($this->xlite->get('gcheckout_remove_discounts')) {
            if ($this->isShowRemoveDiscountsNote()) {
                return true;
            }
        }

        return $this->IsShowNotValidDiscountNote();
    }

    function isShowRemoveDiscountsNote()
    {
        if (!$this->xlite->get('gcheckout_remove_discounts')) {
            return false;
        }

        if (
            $this->xlite->get('PromotionEnabled')
            && (!is_null($this->getDC()) || $this->get('payedByPoints') > 0)
        ) {
            return true;
        }

        if ($this->xlite->get('GiftCertificatesEnabled') && !is_null($this->getGC())) {
            return true;
        }

        return false;
    }

    function IsShowNotValidDiscountNote()
    {
        if ($this->xlite->get('gcheckout_remove_discounts')) {
            if ($this->isShowRemoveDiscountsNote()) {
                return false;
            }
        }

        return !$this->is('googleMeetDiscount');
    }

    function update()
    {
        $this->googleDisableNotification(true);
        $result = parent::update();
        $this->googleDisableNotification(false);

        return $result;
    }

}
