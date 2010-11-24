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

namespace XLite\Model\PaymentMethod;

/**
 * CreditCard-based  / web-based payment method
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class CreditCardWebBased extends \XLite\Model\PaymentMethod\CreditCard
{
    /**
     * Form template
     *
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $formTemplate = false;

    /**
     * Get form URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function getFormURL();

    /**
     * Get form method (POST / GET)
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFormMethod()
    {
        return 'POST';
    }

    /**
     * Get form fields 
     *
     * @param \XLite\Model\Cart $cart $cart
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getFields(\XLite\Model\Cart $cart);

    /**
     * Handle request
     *
     * @param \XLite\Model\Cart $cart Cart
     * @param string            $type Call type OPTIONAL
     *
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest(\XLite\Model\Cart $cart, $type = self::CALL_CHECKOUT)
    {
        if (self::CALL_CHECKOUT == $type) {
            $this->displayRedirectPage($cart);
        }

        parent::handleRequest($cart, $type);
    }

    /**
     * Get default return URL 
     *
     * @param string $fieldName Order id field name OPTIONAL
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getReturnURL($fieldName = 'order_id')
    {
        return \XLite::getInstance()->getShopUrl(
            \XLite\Core\Converter::buildUrl('callback', 'callback', array('order_id_name' => $fieldName)),
            \XLite\Core\Request::getInstance()->isHTTPS()
        );
    }

    /**
     * Get client IP 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getClientIP()
    {
        $result = null;

        if (
            isset($_SERVER['REMOTE_ADDR'])
            && preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/Ss', $_SERVER['REMOTE_ADDR'])
        ) {
            $result = $_SERVER['REMOTE_ADDR'];
        }

        return $result;
    }

    /**
     * Check total (cart total and transaction total from gateway response)
     * 
     * @param \XLite\Model\Cart $cart  Cart
     * @param float             $total Total from gateway response
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkTotal(\XLite\Model\Cart $cart, $total)
    {
        $result = true;

        if ($total && $cart->get('total') != $total) {
            $cart->setDetailsCell('error', 'Error', 'Hacking attempt!');
            $cart->setDetailsCell(
                'errorDescription',
                'Hacking attempt details',
                'Total amount doesn\'t match. Order total: ' . $cart->get('total')
                . '; payment gateway amount: ' . $total
            );
            $result = false;
        }

        return $result;
    }

    /**
     * Check currency (payment method curreny and transaction response currency)
     * 
     * @param \XLite\Model\Cart $cart            Cart
     * @param string            $paymentCurrency Order currency code
     * @param string            $currency        Transaction response currency code
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkCurrency(\XLite\Model\Cart $cart, $paymentCurrency, $currency)
    {
        $result = true;

        if ($currency && $paymentCurrency != $currency) {
            $cart->setDetailsCell('error', 'Error', 'Hacking attempt!');
            $cart->setDetailsCell(
                'errorDescription',
                'Hacking attempt details',
                'Currency code doesn\'t match. Order currency: ' . $paymentCurrency
                . '; payment gateway currency: ' . $currency
            );

            $result = false;
        }

        return $result;
    }

    /**
     * Display redirect page
     *
     * @param \XLite\Model\Cart $cart Cart
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function displayRedirectPage(\XLite\Model\Cart $cart)
    {
        $method = $this->getFormMethod();
        $url = $this->getFormURL();

        $inputs = array();
        foreach ($this->getFields($cart) as $name => $value) {
            $inputs[] = '<input type="hidden" name="' . htmlspecialchars($name)
                . '" value="' . htmlspecialchars($value) . '" />';
        }

        $body = implode("\n", $inputs);

        $page = <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body onload="javascript: document.getElementById('form').submit();">
<form method="$method" id="form" name="payment_form" action="$url">
$body
<noscript>
If you are not redirected within 3 seconds, please <input type="submit" value="press here" />.
</noscript>
</form>
</body>
</html>
HTML;

        echo ($page);

        exit (0);
    }

    /**
     * Display return page 
     * 
     * @param \XLite\Model\Cart $cart Cart_
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function displayReturnPage(\XLite\Model\Cart $cart)
    {
        $backUrl = $this->xlite->getShopUrl(
            \XLite\Core\Converter::buildURL(
                'checkout',
                'return',
                array('order_id' => $cart->get('order_id'))
            ),
            $this->config->Security->customer_security
        );


        $page = <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body onload="javascript: document.location = '$backUrl;';">
<noscript>
If you are not redirected within 5 seconds, please <a href="$backUrl">click here to return to the shopping cart</a>.
</noscript>
</body>
</html>
HTML;

        echo ($page);

        exit (0);
    }
}
