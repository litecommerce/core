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
class XLite_Module_WorldPay_Model_PaymentMethod_Worldpay extends XLite_Model_PaymentMethod_CreditCard
{
    public $configurationTemplate = "modules/WorldPay/config.tpl";
    public $formTemplate = "modules/WorldPay/checkout.tpl";
    public $processorName = "RBS WorldPay";
    public $hasConfugurationForm = true;

    function handleRequest(XLite_Model_Cart $cart)
    {
        require_once LC_MODULES_DIR . 'WorldPay' . LC_DS . 'encoded.php';
        func_PaymentMethod_worldpay_handleRequest($this, $cart);
    }

    function getWorldPayURL()
    {
        return ($this->getComplex('params.test') == "N") ? 'https://select.wp3.rbsworldpay.com/wcc/purchase' : "https://select-test.wp3.rbsworldpay.com/wcc/purchase";
    }

    function getTestMode()
    {
        return ($this->getComplex('params.test') == "N") ? "0" : "100";
    }

    function getCartId($oid)
    {
        return $this->getComplex('params.prefix').$oid;
    }

    function getNameField($cart)
    {
        switch ($this->getComplex('params.test')) {
            case 'A':
                $result = 'AUTHORISED';
                break;
            case 'R':
                $result = 'REFUSED';
                break;
            case 'E':
                $result = 'ERROR';
                break;
            case 'C':
                $result = 'CAPTURED';
                break;
            default:
                $result = $cart->profile->get("billing_firstname") . " " . $cart->profile->get("billing_lastname");
                break;
        }
        return $result;

    }

    /* calculate MD5 signature for transaction.
     * the same md5hashValue should be set on your WorldPay CMS. 
     */
    function getMD5Signature($cart)
    {
        if (!is_null($this->getComplex('params.md5HashValue'))) {
   
            $plain = $this->getComplex('params.md5HashValue') . ':' .
                $this->formatTotal($cart->get('total')) . ':' .
                $this->getComplex('params.currency') . ':' .
                $this->getCartId($cart->get('order_id'));
            $md5sum = md5($plain);
            $this->logger->log("Worldpay:getMD5Signature($plain): $md5sum");
            return $md5sum;
        }
        return NULL;
    }

    function formatTotal($total)
    {
        return number_format($total, 2, '.', '');
    }

    function handleConfigRequest()
    {
        if (!isset($_POST['params']['check_total'])) $_POST['params']['check_total'] = 0;
        if (!isset($_POST['params']['check_currency'])) $_POST['params']['check_currency'] = 0;
        parent::handleConfigRequest();
    }
}
