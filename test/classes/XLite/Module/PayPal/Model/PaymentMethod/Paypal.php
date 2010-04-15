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
class XLite_Module_PayPal_Model_PaymentMethod_Paypal extends XLite_Model_PaymentMethod
{	
    public $pendingReasons = array(
        'echeck' => 'The payment is pending because it was made by an eCheck, which has not yet cleared',
        'multi_currency' => 'You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment',
        'intl' => 'The payment is pending because you, the merchant, hold an international account and do not have a withdrawal method.  You must manually accept or deny this payment from your Account Overview',
        'verify' => 'The payment is pending because you, the merchant, are not yet verified. You must verify your account before you can accept this payment',
        'address' => 'The payment is pending because your customer did not include a confirmed shipping address and you, the merchant, have your Payment Receiving Preferences set such that you want to manually accept or deny each of these payments.  To change your preference, go to the Preferences section of your Profile',
        'upgrade' => 'The payment is pending because it was made via credit card and you, the merchant, must upgrade your account to Business or Premier status in order to receive the funds',
        'unilateral' => 'The payment is pending because it was made to an email address that is not yet registered or confirmed',
        'other' => 'The payment is pending for some reason. For more information, contact PayPal customer service'
        );	

    public $configurationTemplate = "modules/PayPal/config.tpl";	
    public $formTemplate = "modules/PayPal/checkout.tpl";	
    public $processorName = "PayPal";

    function handleRequest($order)
    {
        require_once LC_MODULES_DIR . 'PayPal' . LC_DS . 'encoded.php';
        PaymentMethod_paypal_handleRequest($this, $order);
    }

    function parsePhone($profile)
    {
        $phone = $profile->get("billing_phone");
        $phone = preg_replace("/[ ()-]/", "", $phone);
        return $phone;
    }
    
    function getDayPhoneA($profile) 
    {
        $phone = $this->parsePhone($profile);
        return substr($phone, -10,-7);
    }

    function getDayPhoneB($profile) 
    {
        $phone = $this->parsePhone($profile);
        return substr($phone, -7, -4);
    }

    function getDayPhoneC($profile) 
    {
        $phone = $this->parsePhone($profile);
        return substr($phone, -4);
    }

    function getItemName($order)
    {
        return $this->config->getComplex('Company.company_name') . " order #" . $order->get("order_id");
    }
}
