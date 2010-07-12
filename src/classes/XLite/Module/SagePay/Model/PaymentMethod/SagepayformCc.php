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

namespace XLite\Module\SagePay\Model\PaymentMethod;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class SagepayformCc extends \XLite\Model\PaymentMethod\CreditCard
{
    public $processorName = "SagePay VSP Form";
    public $hasConfigurationForm = true;
    public $configurationTemplate = "modules/SagePay/config.tpl";

    function handleRequest(\XLite\Model\Cart $cart)
    {
        require_once LC_MODULES_DIR . 'SagePay' . LC_DS . 'encoded.php';
        PaymentMethod_SagePayForm_handleRequest($this, $cart);
    }

    function getFormTemplate()
    {
        return "modules/SagePay/checkout.tpl";
    }

    function getSuccessUrl($order_id)
    {
        return $this->xlite->getShopUrl("cart.php?target=sagepayform_checkout&action=return", $this->config->Security->customer_security);
    }

    function getFailureUrl($order_id)
    {
        return $this->xlite->getShopUrl("cart.php?target=sagepayform_checkout&action=return&failed=1", $this->config->Security->customer_security);
    }

    function get($name)
    {
        if ($name == "params") {
            $pm = \XLite\Model\PaymentMethod::factory('sagepaydirect_cc');
            return $pm->get('params');
        }
        if (preg_match("/order.*status/i", $name, $matches)) {
            $pm = \XLite\Model\PaymentMethod::factory('sagepaydirect_cc');
            return $pm->get($matches[0]);
        }

        return parent::get($name);
    }


//////////// Fill "SagePay VSP Form" form methods ////////////
    function getVendorName()
    {
        return $this->getComplex('params.vendor_name');
    }

    function getFormPostUrl($is_simulator=false)
    {
        if ($is_simulator) {
            return "https://test.sagepay.com/Simulator/VSPFormGateway.asp";
        }
        $subtag = (($this->getComplex('params.testmode') == "N") ? "live" : "test");
        return "https://$subtag.sagepay.com/gateway/service/vspform-register.vsp";
    }

    function getCryptedInfo($cart)
    {
        require_once LC_MODULES_DIR . 'SagePay' . LC_DS . 'encoded.php';

        return func_SagePayForm_compileInfoCrypt($this, $cart);
    }

    function getPaymentType()
    {
        if (in_array($this->getComplex('params.trans_type'), array('PAYMENT', "DEFERRED", "AUTHENTICATE")))
            return $this->getComplex('params.trans_type');

        return "AUTHENTICATE";
    }

}
