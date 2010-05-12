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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_ePDQ_Model_PaymentMethod_EpdqCc extends XLite_Model_PaymentMethod_CreditCard
{
    public $configurationTemplate = "modules/ePDQ/config.tpl";
    public $formTemplate = "modules/ePDQ/checkout.tpl";
    public $hasConfigurationForm = true;
    public $processorName = "ePDQ";

    function handleRequest(XLite_Model_Cart $cart)
    {
        require_once LC_MODULES_DIR . 'ePDQ' . LC_DS . 'encoded.php';
        func_PaymentMethod_epdq_cc_handleRequest($this, $cart);
    }

    function getePDQdata($cart)
    {
        $merchant = $this->getComplex('params.param01');
        $clientid = $this->getComplex('params.param02');
        $phrase   = $this->getComplex('params.param03');
        $currency = $this->getComplex('params.param04');
        $auth     = $this->getComplex('params.param05');
        $cpi_logo = $this->getComplex('params.param06');
        $ordr = $cart->get("order_id");

#the following parameters have been obtained earlier in the merchant's webstore: clientid, passphrase, oid, currencycode, total
        $_params="clientid=" . $clientid;
        $_params.="&password=" . $phrase;
        $_params.="&oid=" . $ordr;
        $_params.="&chargetype=" . $auth;
        $_params.="&currencycode=" . $currency;
        $_params.="&total=" . $cart->get("total");

#perform the HTTP Post

        $request = new XLite_Model_HTTPS();
        $request->urlencoded = true;
        $request->url = $this->getComplex('params.param08');
        $request->data = $_params;
        $request->request();
        return $request->response;
    }
}
