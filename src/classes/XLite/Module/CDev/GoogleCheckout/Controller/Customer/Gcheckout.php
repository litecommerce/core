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

namespace XLite\Module\CDev\GoogleCheckout\Controller\Customer;

/**
 * Google checkout main controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Gcheckout extends \XLite\Controller\Customer\Checkout
{
    function init()
    {
        $this->registerForm = new \XLite\Base();
        parent::init();
    }

    /**
     * Handles the request
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        $gacObject = new \XLite\Module\CDev\GoogleCheckout\View\GoogleAltCheckout();
        $gacObject->initGoogleData();

        if ($this->action != 'checkout' || !isset($gacObject->GCMerchantID)) {
            $this->redirect($this->buildUrl('cart'));

        } else {
            parent::handleRequest();
        }
    }

    /**
     * Checkout 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionCheckout()
    {
        // redirect to cart if not allowed for GoogleCheckout
        if (!$this->cart->isGoogleAllowPay()) {
            $this->redirect($this->buildUrl('cart'));

            return;
        }

        if ($this->xlite->get('gcheckout_remove_discounts')) {
            if ($this->xlite->get('PromotionEnabled')) {
                $this->cart->setDC(null);
                $this->cart->set('payedByPoints', 0);
            }

            if ($this->xlite->get('GiftCertificatesEnabled')) {
                $this->cart->setGC(null);
            }

        } else {
            if ($this->xlite->get('PromotionEnabled')) {
                if (!$this->cart->is('googleMeetDiscount')) {
                    $this->cart->setDC(null);
                }
            }
        }

        $pm = \XLite\Model\PaymentMethod::factory('google_checkout');
        $this->cart->setPaymentMethod($pm);
        $this->updateCart();

        $result = $pm->sendGoogleCheckoutRequest($this->cart);

        if (isset($result['CHECKOUT-REDIRECT']) && isset($result['CHECKOUT-REDIRECT']['REDIRECT-URL']) ) {
            $url = $result['CHECKOUT-REDIRECT']['REDIRECT-URL'];
            // when PHP5 is used with libxml 2.7.1, HTML entities are stripped from any XML content
            // this is a workaround for https://qa.mandriva.com/show_bug.cgi?id=43486
            if (strpos($url, 'shoppingcartshoppingcart') !== false) {
                $url = str_replace('shoppingcartshoppingcart', 'shoppingcart&shoppingcart', $url);
            }
            $this->set('silent', true);
            $this->xlite->done();
            header('Location: ' . $url);
            exit ();

        } else {
            $this->set('valid', false);
            if (isset($result['ERROR']) && isset($result['ERROR']['ERROR-MESSAGE']) ) {
                $this->set('googleError', $result['ERROR']['ERROR-MESSAGE']);
            }
        }
    }
}
