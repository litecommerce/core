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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Google checkout alternative widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GoogleCheckout_View_GoogleAltCheckout extends XLite_View_AView
{
    public $GCMerchantID = null;
    public $GCMerchantKey = null;
    public $CurrentSkin = null;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/GoogleCheckout/main_alt_checkout.tpl';
    }

    function initGoogleData()
    {
        if (!isset($this->GCMerchantID)) {
            $pm = XLite_Model_PaymentMethod::factory('google_checkout');
            $isAdminZone = $this->xlite->is('adminZone');
            $this->xlite->set('adminZone', true);
            $enabled = (bool) $pm->get('enabled');
            $this->xlite->set('adminZone', $isAdminZone);
            if ($enabled) {
                $params = $pm->get('params');
                $this->GCMerchantID = $params['merchant_id'];
                $this->GCMerchantKey = $params['merchant_key'];
                $this->CurrentSkin = strval($this->getComplex('dialog.config.Skins.skin'));
            } else {
                $this->GCMerchantID = null;
            }
        }
    }

    function isVisible()
    {
        $targetsProfile = array
        (
            "profile",
            "login",
        );
        $targets = array(
            "checkout",
            "cart"
        );
        $cart = $this->getComplex('dialog.cart');
        $dialogTarget = $this->getComplex('dialog.target');
        if (is_object($cart) && !$cart->is('empty') && !in_array($dialogTarget, $targets)) {
            if (in_array($dialogTarget, $targetsProfile)) {
                $this->setComplex('dialog.google_checkout_profile', true);
            }
            $this->initGoogleData();

            return isset($this->GCMerchantID);

        } else {
            return false;
        }
    }

    function getGoogleCheckoutButtonUrl($variant='medium', $background='white')
    {
        // Available button styles
        $backgrounds = array('white'=>'white', 'transparent'=>'trans');
        $variants = array(
                'large' => array('width'=>180, 'height'=>46),
                'medium' => array('width'=>168, 'height'=>44),
                'small' => array('width'=>160, 'height'=>43),
                'mobile-hi' => array('width'=>152, 'height'=>30),
                'mobile-low' => array('width'=>118, 'height'=>24),
            );

        // Chosen button style    
        $background = $backgrounds[$background];
        $variant = $variants[$variant];
        $width = $variant['width'];
        $height = $variant['height'];

        $enabled = $this->isGoogleAllowPay() ? 'text' : 'disabled';
        $protocol = ($this->getComplex('dialog.secure')) ? 'https' : 'http';
        $merchant_id = $this->GCMerchantID;

        return "$protocol://checkout.google.com/buttons/checkout.gif?merchant_id=$merchant_id&w=$width&h=$height&style=$background&variant=$enabled&loc=en_US";

    }

    function getGoogleCheckoutButtonImgNum()
    {
        $imgMap = array
        (
            ""  => 1,
            "2-columns_modern"  => 1,
            "3-columns_modern"  => 2,
            "2-columns_classic" => 3,
            "3-columns_classic" => 4,
        );

        if (in_array($this->CurrentSkin, $imgMap)) {
            $vlaue = $imgMap[$this->CurrentSkin];
        } else {
            $vlaue = 1;
        }

        return $vlaue;
    }

    function isGoogleAllowPay()
    {
        $cart = $this->getComplex('dialog.cart');
        if (is_null($cart) || !is_object($cart)) {
            $cart = XLite_Model_Cart::getInstance();
        }

        return $cart->isGoogleAllowPay();
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
