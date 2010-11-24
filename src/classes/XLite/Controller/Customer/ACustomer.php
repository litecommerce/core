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

namespace XLite\Controller\Customer;

/**
 * Abstract controller for Customer interface
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ACustomer extends \XLite\Controller\AController
{
    /**
     * cart 
     * 
     * @var    \XLite\Model\Cart
     * @access protected
     * @since  3.0.0
     */
    protected $cart;

    /**
     * Initial cart fingerprint 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $initialCartFingerprint;

    /**
     * Stub for the CMS connectors
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function checkStorefrontAccessability()
    {
        return true;
    }

    /**
     * Perform some actions to prohibit access to storefornt 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function closeStorefront()
    {
        include LC_SKINS_DIR . '/storefront_closed.html';
        exit (0);
    }

    /**
     * Return template to use in a CMS
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCMSTemplate()
    {
        return 'center_top.tpl';
    }

    /**
     * Select template to use
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getViewerTemplate()
    {
        return $this->getParam(self::PARAM_IS_EXPORTED) ? $this->getCMSTemplate() : parent::getViewerTemplate();
    }

    /**
     * Recalculates the shopping cart
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function updateCart()
    {
        $cart = $this->getCart();
        \XLite\Core\Database::getEM()->flush();

        $total = $cart->getTotal();

        $cart->normalizeItems();
        $cart->calculate();

        if ($total != $cart->getTotal()) {
            $cart->renewPaymentMethod();
        }

        \XLite\Core\Database::getEM()->flush();

        $this->assembleEvent();

        $this->initialCartFingerprint = $this->getCart()->getEventFingerprint();
    }

    /**
     * Assemble updateCart event 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function assembleEvent()
    {
        $result = false;

        $old = $this->initialCartFingerprint;
        $new = $this->getCart()->getEventFingerprint();
        $items = array();

        // Assembly changed
        foreach ($new['items'] as $n => $cell) {
            $found = false;
            foreach ($old['items'] as $i => $oldCell) {
                if ($cell['key'] == $oldCell['key']) {
                    if ($cell['quantity'] != $oldCell['quantity']) {
                        $cell['quantity_change'] = $cell['quantity'] - $oldCell['quantity'];
                        $items[] = $cell;
                    }

                    unset($old['items'][$i]);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $cell['quantity_change'] = $cell['quantity'];
                $items[] = $cell;
            }
        }

        // Assemble removed
        foreach ($old['items'] as $cell) {
            $cell['quantity_change'] = $cell['quantity'] * -1;
            $items[] = $cell;
        }

        if ($items) {
            \XLite\Core\Event::updateCart(array('items' => $items));
            $result = true;
        }

        return $result;
    }

    /**
     * isCartProcessed 
     * 
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function isCartProcessed()
    {
        return $this->getCart()->isProcessed() || $this->getCart()->isQueued();
    }

    /**
     * Return current (or default) product object
     * 
     * @return \XLite\Model\Product
     * @access public
     * @since  3.0.0
     */
    public function getProduct()
    {
        $product = parent::getProduct();

        return $product->getEnabled() ? $product : null;
    }

    /**
     * Return cart instance 
     * 
     * @return \XLite\Model\Order
     * @access public
     * @since  3.0.0
     */
    public function getCart()
    {
        return \XLite\Model\Cart::getInstance();
    }

    /**
     * Get the full URL of the page
     * Example: getShopUrl('cart.php') = "http://domain/dir/cart.php 
     * 
     * @param string $url    relative URL  
     * @param boolean   $secure flag to use HTTPS
     *  
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getShopUrl($url, $secure = false)
    {
        $currentSecurity = $this->config->Security->full_customer_security;

        return parent::getShopUrl($url, $currentSecurity ? $currentSecurity : $secure);
    }

    /**
     * Check - use secure (HTTPS) connection or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSecure()
    {
        $result = parent::isSecure();

        if (!is_null($this->get('feed')) && $this->get('feed') == 'login') {
            $result = $this->config->Security->customer_security;
        }

        return $result;
    }

    /**
     * Get external link 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getExternalLink()
    {
        return $this->buildURL(
            $this->getTarget(),
            '',
            $this->getParamsHash(array_keys($this->getWidgetSettings()))
        );
    }

    /**
     * Get controller charset 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCharset()
    {
        $charset = false;

        if ($this->getCart() && $this->getCart()->getProfile()) {
            $charset = $this->getCart()->getProfile()->getBillingAddress()->getCountry()->getCharset();
        }

        return $charset ? $charset : parent::getCharset();
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
        if (!$this->checkStorefrontAccessability()) {
            $this->closeStorefront();
        }

        // Save initial cart fingerprint
        $this->initialCartFingerprint = $this->getCart()->getEventFingerprint();

        return parent::handleRequest();
    }

    /**
     * Get or create cart profile 
     * 
     * @return \XLite\Model\Profile
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCartProfile()
    {
        $profile = $this->getCart()->getProfile();

        if (!$profile) {
            $profile = new \XLite\Model\Profile;
            $profile->setOrderId($this->getCart()->getorderId());
            $profile->create();

            $this->getCart()->setProfile($profile);

            \XLite\Core\Auth::getInstance()->loginProfile($profile);

            \XLite\Core\Database::getEM()->persist($profile);
            \XLite\Core\Database::getEM()->flush();
        }

        return $profile;
    }

}

