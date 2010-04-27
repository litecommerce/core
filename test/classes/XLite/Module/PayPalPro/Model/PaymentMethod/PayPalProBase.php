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
 * PayPal Pro base payment method class
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Module_PayPalPro_Model_PaymentMethod_PayPalProBase extends XLite_Model_PaymentMethod
{
    /**
     * XPath response object
     * 
     * @var    DOMXPath
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $xpath = null;

    /**
     * Last HTTPS request error 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $lastRequestError = false;


    /**
     * Send request 
     * 
     * @param array  $payment Payment module parameters
     * @param string $data    XML data
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sendRequest(array $payment, $data)
    {
        $this->lastRequestError = false;

        $request = new XLite_Model_HTTPS();

        $request->data       = $data;
        if ('C' == $payment['auth_method']) {
            $request->cert = $payment['certificate'];
        }
        $request->method     = 'POST';
        $request->conttype   = 'text/xml';
        $request->urlencoded = true;

        if ($payment['mode']) {
            $request->url = 'C' == $payment['auth_method']
                ? 'https://api.paypal.com:443/2.0/'
                : 'https://api-3t.paypal.com:443/2.0/';

        } else {
            $request->url = 'C' == $payment['auth_method']
                ? 'https://api.sandbox.paypal.com:443/2.0/'
                : 'https://api-aa.sandbox.paypal.com:443/2.0/';
        }

        $request->request();

        if ($request->error) {
            $this->lastRequestError = $request->error;
        }

        return $request->error ? $request->error : $request->response;
    }

    /**
     * Get cancel URL
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCancelUrl()
    {
        $url = $this->xlite->getShopUrl(
            XLite_Core_Converter::buildUrl('checkout', 'paypal_cancel'),
            $this->config->Security->customer_security
        );

        return $this->prepareUrl($url);
    }

    /**
     * Get notify (callback) URL 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNotifyUrl()
    {
        $url = $this->xlite->getShopUrl(
            XLite_Core_Converter::buildUrl('callback', 'callback')
        );

        return $this->prepareUrl($url);
    }

    /**
     * Prepare URL 
     * 
     * @param string $url URL
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareUrl($url)
    {
        return htmlspecialchars($url);
    }

    /**
     * Get request signature XML tag
     * 
     * @param array $payment Payment module data
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRequestSignature(array $payment)
    {
        return 'C' != $payment['auth_method'] ? ('<Signature>' . $payment['signature'] . '</Signature>') : '';
    }

    /**
     * Parse response 
     * 
     * @param string $response Response
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function parseResponse($response)
    {
        $dom = new DOMDocument();
        $result = @$dom->loadXML($response, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_ERR_NONE);
        if ($result) {
            $this->xpath = new DOMXPath($dom);

            $this->xpath->registerNamespace('api', 'urn:ebay:api:PayPalAPI');
            $this->xpath->registerNamespace('base', 'urn:ebay:apis:eBLBaseComponents');
        }

        return $result;
    }

    /**
     * Get response XML node value 
     * 
     * @param string  $query  XPath query
     * @param DOMNode $parent Parent query node
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getXMLResponseValue($query, $parent = null)
    {
        return $parent
            ? $this->xpath->query($query, $parent)->item(0)->nodeValue
            : $this->xpath->query($query)->item(0)->nodeValue;
    }

    /**
     * Getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __get($name)
    {
        return 'xpath' == $name ? $this->xpath : parent::__get($name);
    }
}

